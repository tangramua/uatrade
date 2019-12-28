<?php
namespace Modules\Core\Extensions\Doctrine;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManagerInterface;
use Modules\Core\Extensions\Doctrine\Listener\DistinctFixer as DistinctFixerListener;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use LaravelDoctrine\Extensions\GedmoExtension;


class DistinctFixerExtension extends GedmoExtension
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

//    /**
//     * @param Application $application
//     * @param Repository  $repository
//     * @param Dispatcher  $events
//     * @param \Illuminate\Http\Request  $request
//     */
//    public function __construct(Application $application, Repository $repository, Dispatcher $events, Request $request)
//    {
//        $this->application = $application;
//        $this->repository  = $repository;
//        $this->events      = $events;
//        $this->request     = $request;
//    }

    public function addSubscribers(EventManager $manager, EntityManagerInterface $em, Reader $reader = null)
    {
        $subscriber = new DistinctFixerListener();
        $this->addSubscriber($subscriber, $manager, $reader);

        //\Doctrine\DBAL\Events::postConnect
//        $manager->addEventListener(\Doctrine\DBAL\Events::postConnect , function ($a, $b, $c) {
//dd('postConnect', $a, $b, $c);
//        });

    }

    public function getFilters()
    {
        return [];
    }
}