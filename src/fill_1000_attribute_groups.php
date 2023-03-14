<?php

namespace Ghoussard\PimApiScripts;

require_once(__DIR__ . '/../vendor/autoload.php');

use Akeneo\Pim\ApiClient\AkeneoPimClientBuilder;

$url = '';
$clientId = '';
$clientSecret = '';
$username = '';
$password = '';

$clientBuilder = new AkeneoPimClientBuilder($url);
$client = $clientBuilder->buildAuthenticatedByPassword($clientId, $clientSecret, $username, $password);

$attributeGroupApi = $client->getAttributeGroupApi();


$attributeGroupsPage = $attributeGroupApi->listPerPage(withCount: true);
$attributeGroupsCount = $attributeGroupsPage->getCount();

const MAX_ATTRIBUTE_GROUP = 1000;

if (MAX_ATTRIBUTE_GROUP === $attributeGroupsCount) {
  echo 'Attribute groups are filled!';
  return 0;
}

$attributeGroupsToCreate = [];

for ($i = $attributeGroupsCount; $i < MAX_ATTRIBUTE_GROUP; $i++) {
  $attributeGroupsToCreate[] = [
    'code' => "attribute_group_$i",
  ];
}

foreach (array_chunk($attributeGroupsToCreate, 100) as $chunkedAttributeGroupsToCreate) {
  $attributeGroupApi->upsertList($chunkedAttributeGroupsToCreate);
}

echo 'Attribute groups are filled!';
return 0;
