<?php
namespace App\Serializer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\HttpFoundation\File\File;

class FileDenormalizer implements DenormalizerInterface
{

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return $data;
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
      return $data instanceof File;
    }
}
