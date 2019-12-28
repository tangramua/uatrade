<?php

namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Doctrine\Common\Inflector\Inflector;
use RepoFactory;
use Validator;
use QRCode;
use Storage;
use Log;
use View;
use \Exception;
use Modules\Company\Entities\Doctrine\Company;
use Modules\Company\Entities\Doctrine\Employee;
use Modules\Event\Entities\Doctrine\Event;

use Module;

class CollectEntityStaticData extends Command
{
    const RESULT_FILE_RELATIVE_PATH = 'info';

    const DEFAULT_ENTITY_TEMPLATE_VIEW = 'core::info.default';

    const GENERAL_HTML_VIEW = 'core::info.index';

    const ENTITY_ID_PLACEHOLDER = '{id}';

    const AVAILABLE_ENTITIES = [
        'event' => Event::class,
        'company' => Company::class
    ];

    protected $entityQrCodeFileName = 'qr-code.svg';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'entity:data-collect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generating QR-code by specified url template for specified entities and collecting entities data to html file.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->validateArguments();
        $this->initializeEntityAdditionalSettings();

        $entityClassName = self::AVAILABLE_ENTITIES[$this->argument('entity')];

        $entities = RepoFactory::create($entityClassName)->getAll();

        $viewName = $this->getViewName($entities[0]);

        $entitiesHtmlCollection = [];
        foreach ($entities as $entity) {
            $qrCodeRelativePath = $this->generateQrCode($entity);
            $entitiesHtmlCollection[] = $this->getEntityInfoHtml($viewName, $entity, $qrCodeRelativePath);
        }

        $link = $this->saveAsHtmlFile($entitiesHtmlCollection);

        $this->info("DONE.");
        $this->info("Results are available by link $link");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['entity', InputArgument::REQUIRED, 'Entity class name. Available values: ' . implode(', ', array_keys(self::AVAILABLE_ENTITIES)), null],
            ['url_template', InputArgument::REQUIRED, "Template url for generating QrCode. Template must have protocol and entity id placeholder '" . self::ENTITY_ID_PLACEHOLDER . "'. For example, 'https://gochinait.loc/event/" . self::ENTITY_ID_PLACEHOLDER ."'", null],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

    /**
     * Validate the console command arguments.
     */
    protected function validateArguments()
    {
        $validator = Validator::make([
            'entity' => $this->argument('entity'),
            'url_template' => $this->argument('url_template')
        ], [
            'entity' => ['required', 'in:' . implode(',', array_keys(self::AVAILABLE_ENTITIES))],
            'url_template' => 'required|regex:/^http[s]{0,1}:\/\/\S+\/' . self::ENTITY_ID_PLACEHOLDER . '/',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            die;
        }
    }

    protected function initializeEntityAdditionalSettings()
    {
        switch ($this->argument('entity')) {
            case 'event':
                Employee::$exportCompanyRelation = true;
                break;
        }
    }

    /**
     * @param object $entity
     * @return string
     */
    protected function getViewName(object $entity) :string
    {
        $viewName = sprintf('%s::info.%s',
            $this->getModuleName($entity),
            $this->argument('entity')
        );

        if(!view()->exists($viewName)) {
            $this->warn("View '$viewName' wasn't found. Will be used default template.");
            $viewName = $this->getDefaultViewName();
        }

        return $viewName;
    }

    /**
     * @return string
     */
    protected function getDefaultViewName() :string
    {
        if(!view()->exists(self::DEFAULT_ENTITY_TEMPLATE_VIEW)) {
            $this->error("Fatal error: Default template view '" . self::DEFAULT_ENTITY_TEMPLATE_VIEW . "' not available.");
            die;
        }
        return self::DEFAULT_ENTITY_TEMPLATE_VIEW;
    }

    /**
     * @param object $entity
     * @return string
     */
    protected function getModuleName(object $entity) :string
    {
        return $entity->getModule()->getLowerName();
    }

    /**
     * @param string $viewName
     * @param object $entity
     * @param string $qrCodeRelativePath
     * @return string
     */
    protected function getEntityInfoHtml(string $viewName, object $entity, string $qrCodeRelativePath) :string
    {
        $entity = array_merge($entity->toArray(), [
            'entity_name' => ucfirst($this->argument('entity')),
            'url_for_qr_code' => $this->getUrlForQrCode($entity),
            'qr_code_path' => $qrCodeRelativePath
        ]);

        $view = View::make($viewName, ['entity' => $entity]);

        return $view->render();
    }

    /**
     * @param $entitiesHtmlCollection
     * @return string
     */
    protected function getGeneralHtml($entitiesHtmlCollection) :string
    {
        $viewName = $this->getGeneralHtmlViewName();
        $documentTitle = Inflector::pluralize(ucfirst($this->argument('entity')));

        $view = View::make($viewName, [
            'title' => $documentTitle,
            'entitiesHtmlCollection' => $entitiesHtmlCollection
        ]);

        return $view->render();
    }

    /**
     * @return string
     */
    protected function getGeneralHtmlViewName() :string
    {
        if(!view()->exists(self::GENERAL_HTML_VIEW)) {
            $this->error("Fatal error: General html view '" . self::GENERAL_HTML_VIEW . "' not available.");
            die;
        }
        return self::GENERAL_HTML_VIEW;
    }

    /**
     * @param array $entitiesHtmlCollection
     * @return string
     */
    protected function saveAsHtmlFile(array $entitiesHtmlCollection) :string
    {
        $generalHtml = $this->getGeneralHtml($entitiesHtmlCollection);

        $fileWithRelativePathFromPublic = sprintf('%s/%s.html',
            self::RESULT_FILE_RELATIVE_PATH,
            $this->argument('entity')
        );

        $resultOfSaving = Storage::disk('public')->put($fileWithRelativePathFromPublic, $generalHtml);
        if(!$resultOfSaving) {
            $this->error("Can't save result to the file '$fileWithRelativePathFromPublic'.");
            die;
        }

        return asset('storage/' . $fileWithRelativePathFromPublic);
    }

    /**
     * @param object $entity
     * @return string
     */
    protected function getUrlForQrCode(object $entity) :string
    {
        return str_replace(self::ENTITY_ID_PLACEHOLDER, $entity->getId(), $this->argument('url_template'));
    }

    /**
     * @param object $entity
     * @return string
     * @throws Exception
     */
    protected function getQrCodeFileWithRelativePathFromPublic(object $entity) :string
    {
        $fileRelativePathFromPublic = sprintf('%s/%s',
                $this->argument('entity'),
                $entity->getId()
            );

        $dirExists = Storage::makeDirectory('public/' . $fileRelativePathFromPublic);
        if(!$dirExists) {
            throw new Exception("QR Code for entity with id=" . $entity->getId() . " was not created, because could not create the directory.");
        }

        return $fileRelativePathFromPublic . '/' . $this->entityQrCodeFileName;
    }

    /**
     * @param object $entity
     * @return bool|string
     */
    protected function generateQrCode(object $entity)
    {
        try {
            $url = $this->getUrlForQrCode($entity);

            $qrCodeFileWithRelativePathFromPublic = $this->getQrCodeFileWithRelativePathFromPublic($entity);
            $fileAbsolutePath = Storage::disk('public')->getAdapter()->getPathPrefix() . $qrCodeFileWithRelativePathFromPublic;

            QRCode::url($url)
                ->setSize(10)
                ->setMargin(1)
                ->setOutfile($fileAbsolutePath)
                ->svg();

            return 'storage/' . $qrCodeFileWithRelativePathFromPublic;
        } catch (Exception $e) {
            $this->error($e->getMessage());
            Log::error((string) $e);
            return false;
        }
    }
}
