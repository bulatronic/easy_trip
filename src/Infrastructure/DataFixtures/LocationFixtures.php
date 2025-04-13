<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\Entity\Location;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LocationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $locations = [
            [
                'name' => 'Уфа',
                'type' => 'administrative_center',
                'latitude' => 54.735152,
                'longitude' => 55.958736,
            ],
            [
                'name' => 'Стерлитамак',
                'type' => 'city',
                'latitude' => 53.630562,
                'longitude' => 55.931576,
            ],
            [
                'name' => 'Салават',
                'type' => 'city',
                'latitude' => 53.361631,
                'longitude' => 55.924555,
            ],
            [
                'name' => 'Нефтекамск',
                'type' => 'city',
                'latitude' => 56.088380,
                'longitude' => 54.248192,
            ],
            [
                'name' => 'Октябрьский',
                'type' => 'city',
                'latitude' => 54.481213,
                'longitude' => 53.465631,
            ],
            [
                'name' => 'Белорецк',
                'type' => 'city',
                'latitude' => 53.967117,
                'longitude' => 58.398475,
            ],
            [
                'name' => 'Туймазы',
                'type' => 'city',
                'latitude' => 54.599826,
                'longitude' => 53.694463,
            ],
            [
                'name' => 'Ишимбай',
                'type' => 'city',
                'latitude' => 53.454631,
                'longitude' => 56.043874,
            ],
            [
                'name' => 'Кумертау',
                'type' => 'city',
                'latitude' => 52.765689,
                'longitude' => 55.784316,
            ],
            [
                'name' => 'Мелеуз',
                'type' => 'city',
                'latitude' => 52.959595,
                'longitude' => 55.928738,
            ],
        ];

        foreach ($locations as $locationData) {
            $location = new Location();
            $location->setName($locationData['name']);
            $location->setType($locationData['type']);
            $location->setLatitude($locationData['latitude']);
            $location->setLongitude($locationData['longitude']);

            $manager->persist($location);
        }

        $manager->flush();
    }
}
