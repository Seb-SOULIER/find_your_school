<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;

class Api
{
    public const STATUS_CODE = 200;

    public function searchSchool($student): array
    {
        $lat = $student->getLatitude();
        $lon = $student->getLongitude();
        $dist = 5000;
        $etablissement = $student->getCursus()->getEtablissement();

        $url = "https://data.education.gouv.fr/api/records/1.0/search/"
                ."?dataset=fr-en-annuaire-education&q=&rows=100"
                ."&geofilter.distance=".$lat.",".$lon.",".$dist // Distance par rapport a des coordonnées
                ."&refine.type_etablissement=".$etablissement; // Collège Lycée Ecole
        return $this->getResponse($url);
    }

    public function localize($city): array
    {
        if ($city) {
            $url = "https://nominatim.openstreetmap.org/search?q="
                . $city . "&format=json&addressdetails=1&limit=1";
            return $this->getResponse($url);
        } else {
            return [0 => ['lat' => 46.16, 'lon' => 3.19619]];
        }
    }

    public function getResponse(string $url): array
    {
        $client = HttpClient::create();
        $response = $client->request('GET', $url);
        $statusCode = $response->getStatusCode();
        if ($statusCode === self::STATUS_CODE) {
            // get the response in JSON format
            return $response->toArray();
            // convert the response (here in JSON) to an PHP array
        }
        return ['status' => $response->getStatusCode()];
    }
}

//                ."&facet=pial"
//    ."&facet=identifiant_de_l_etablissement"
//    ."&facet=nom_etablissement"
//    ."&facet=type_etablissement"
//    ."&facet=statut_public_prive"
//    ."&facet=code_commune"
//    ."&facet=nom_commune"
//    ."&facet=code_departement"
//    ."&facet=code_academie"
//    ."&facet=code_region"
//    ."&facet=ecole_maternelle"
//    ."&facet=ecole_elementaire"
//    ."&facet=voie_generale"
//    ."&facet=voie_technologique"
//    ."&facet=voie_professionnelle"
//    ."&facet=restauration"
//    ."&facet=hebergement"
//    ."&facet=ulis"
//    ."&facet=apprentissage"
//    ."&facet=segpa"
//    ."&facet=section_arts"
//    ."&facet=section_cinema"
//    ."&facet=section_theatre"
//    ."&facet=section_sport"
//    ."&facet=section_internationale"
//    ."&facet=section_europeenne"
//    ."&facet=lycee_agricole"
//    ."&facet=lycee_militaire"
//    ."&facet=lycee_des_metiers"
//    ."&facet=post_bac"
//    ."&facet=appartenance_education_prioritaire"
//    ."&facet=greta"
//    ."&facet=type_contrat_prive"
//    ."&facet=libelle_departement"
//    ."&facet=libelle_academie"
//    ."&facet=libelle_region"
//    ."&facet=nom_circonscription"
//    ."&facet=precision_localisation"
//    ."&facet=etat"
//    ."&facet=ministere_tutelle"
//    ."&facet=multi_uai"
//    ."&facet=rpi_concentre"
//    ."&facet=rpi_disperse"
//    ."&facet=code_nature"
//    ."&facet=libelle_nature"
//    ."&facet=code_type_contrat_prive"
//    ."&facet=pial"
//."&refine.code_departement=017"
