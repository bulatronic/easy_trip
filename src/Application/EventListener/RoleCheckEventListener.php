<?php

namespace App\Application\EventListener;

use App\Controller\Security\RequireRole;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

readonly class RoleCheckEventListener
{
    public function __construct(
        private Security $security,
    ) {
    }

    /**
     * @throws \ReflectionException
     */
    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();
        if (is_array($controller)) {
            $controller = $controller[0];
        }

        $reflectionClass = new \ReflectionClass($controller);
        $reflectionMethod = $reflectionClass->getMethod('__invoke');

        $classAttributes = $reflectionClass->getAttributes(RequireRole::class);
        $methodAttributes = $reflectionMethod->getAttributes(RequireRole::class);

        $attributes = [...$classAttributes, ...$methodAttributes];

        foreach ($attributes as $attribute) {
            /** @var RequireRole $requireRole */
            $requireRole = $attribute->newInstance();

            foreach ($requireRole->roles as $role) {
                if (!$this->security->isGranted($role)) {
                    throw new AccessDeniedHttpException('Access Denied.');
                }
            }
        }
    }
}
