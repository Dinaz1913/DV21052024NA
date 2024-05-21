<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;

function getTheGender(string $name):string {
    $client = new Client();
    $response = $client->request('GET', 'https://api.genderize.io', [
        'query' => ['name' => $name]
    ]);

    $data = json_decode($response->getBody());
    if (isset($data->gender)) {
        $gender = $data->gender;
        $probability = $data->probability * 100;
        return "Name $name is $probability% $gender.";
    } else {
        return "Gender information for name $name is not available.";
    }
}

function getTheAge(string $name):string {
    $client = new Client();
    $response = $client->request('GET', 'https://api.agify.io', [
        'query' => ['name' => $name]
    ]);

    $data = json_decode($response->getBody());
    if (isset($data->age)) {
        $age = $data->age;
        return "The estimated age for name $name is $age.";
    } else {
        return "Age information for name $name is not available.";
    }
}

function getTheNationality(string $name):string {
    $client = new Client();
    $response = $client->request('GET', 'https://api.nationalize.io', [
        'query' => ['name' => $name]
    ]);

    $data = json_decode($response->getBody());
    if (isset($data->country[0]->country_id)) {
        $countryCode = $data->country[0]->country_id;
        $countryName = getCountryName($countryCode);
        return "The estimated nationality for name $name is $countryName.";
    } else {
        return "Nationality information for name $name is not available.";
    }
}
function getCountryName($countryCode) {
    if (class_exists('Locale')) {
        return \Locale::getDisplayRegion('-' . $countryCode, 'en');
    } else {
        return $countryCode;
    }
}

echo "Write your name: ";
$handle = fopen("php://stdin", "r");
$name = trim(fgets($handle));  // Read input and remove whitespace

$genderResponse = getTheGender($name);
$ageResponse = getTheAge($name);
$nationalityResponse = getTheNationality($name);

echo $genderResponse . "\n";
echo $ageResponse ."\n";
echo $nationalityResponse . "\n";