<?php

class Organization
{
    /*****************************************************
     ******************** ПОЛЯ КЛАССА ********************
     *****************************************************/

    const SHOW_BY_DEFAULT = 20;

    /*****************************************************
     ******************* МЕТОДЫ КЛАССА *******************
     *****************************************************/

    public static function getAdmPvdOrganizationsByStatus($status = STATUS_ACTUAL)
    {
        global $DB_PARAMETERS;
        $db = Database::getConnection();
        $collection = $db->$DB_PARAMETERS['database']->adm_pvd;

        $field_parameter = array(
            'organizations' => 1
        );

        $find_parameter = array(
            'status' => $status,
        );

        $temp_result = $collection->findOne($find_parameter, $field_parameter);
        $result = array();
        foreach ($temp_result['organizations'] as $organization)
        {
            $result[] = $organization;
        }

        $db->close();
        return $result;
    }
}