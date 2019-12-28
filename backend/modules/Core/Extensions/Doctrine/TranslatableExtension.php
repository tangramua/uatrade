<?php
namespace Modules\Core\Extensions\Doctrine;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Gedmo\Translatable\Query\TreeWalker\TranslationWalker;
use Gedmo\Translatable\TranslatableListener;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Events;
use Illuminate\Http\Request;
use LaravelDoctrine\Extensions\GedmoExtension;
use Modules\Core\Http\Middleware\Localization;

class TranslatableExtension extends GedmoExtension
{
    /**
     * @var Application
     */
    protected $application;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var Dispatcher
     */
    private $events;

    /**
     * @var Request
     */
    private $request;

    /**
     * @param Application $application
     * @param Repository  $repository
     * @param Dispatcher  $events
     * @param \Illuminate\Http\Request  $request
     */
    public function __construct(Application $application, Repository $repository, Dispatcher $events, Request $request)
    {
        $this->application = $application;
        $this->repository  = $repository;
        $this->events      = $events;
        $this->request     = $request;

        $this->triggerMiddlewares();
    }

    public function triggerMiddlewares() {
        app(Localization::class)->handle($this->request, function() {});
    }

    public function addSubscribers(EventManager $manager, EntityManagerInterface $em, Reader $reader = null)
    {
        $subscriber = new TranslatableListener();
        $subscriber->setTranslatableLocale($this->application->getLocale());
        $subscriber->setDefaultLocale($this->repository->get('app.fallback_locale'));
        $subscriber->setTranslationFallback((bool) $this->repository->get('core.translation_fallback_enabled'));

        $this->addSubscriber($subscriber, $manager, $reader);
        $this->events->listen(Events\LocaleUpdated::class, function ($locale) use ($subscriber) {
            if(is_object($locale) && $locale instanceof Events\LocaleUpdated) {
                $locale = $locale->locale;
            }
            $subscriber->setTranslatableLocale($locale);
        });


        $em->getConfiguration()->setDefaultQueryHint(
            TranslatableListener::HINT_TRANSLATABLE_LOCALE,
            app()->getLocale()
        );
        $em->getConfiguration()->setDefaultQueryHint(
            TranslatableListener::HINT_FALLBACK,
            (int) $this->repository->get('core.translation_fallback_enabled') // fallback to default values in case if record is not translated
        );
        $em->getConfiguration()->setDefaultQueryHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            TranslationWalker::class
        );
    }

    public function getFilters()
    {
        return [];
    }
}