<?php

namespace App\EventListener;

use ApiPlatform\Core\EventListener\DeserializeListener as DecoratedListener;
use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class DeserializeListener
{
    public function __construct(
        private SerializerContextBuilderInterface $serializerContextBuilder,
        private DecoratedListener                 $decorated,
        private DenormalizerInterface             $denormalizer
    )
    {
    }

    function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if ($request->isMethodCacheable() || $request->isMethod(Request::METHOD_DELETE)) {
            return;
        }
        if ($request->getContentType() == 'multipart') {
            $this->denormalizeFromRequest($request);
        } else {
            $this->decorated->onKernelRequest($event);
        }
    }


    /**
     * @throws \Nelmio\Alice\Throwable\DenormalizationThrowable
     */
    private function denormalizeFromRequest(Request $request)
    {
        $attributes = RequestAttributesExtractor::extractAttributes($request);

        $populated = $request->attributes->get('data');
        if ($populated !== null) {
            $context['object_to_populate'] = $populated;
        }

        $context = $this->serializerContextBuilder->createFromRequest($request, false, $attributes);

        $object = $this->denormalizer->denormalize(
            array_merge($request->request->all(), $request->files->all()),
            $attributes['resource_class'],
            null,
            $context
        );
        $request->attributes->set('data', $object);
    }
}
