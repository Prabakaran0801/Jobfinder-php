<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

class ListingController
{

    protected $db;

    public function __construct()
    {
        $config = require basePath("config/db.php");
        $this->db =  new Database($config);
    }
    /**
     * Show all listings
     *
     *@return void
     */
    public function index()
    {
        $listings = $this->db->query("SELECT * FROM listings")->fetchAll();
        loadView("listings/index", ["listings" => $listings]);
    }

    /**
     * Show the create listing form
     *
     *@return void
     */

    public function create()
    {
        loadView("listings/create");
    }

    /**
     *Show thw single listing
     *
     *@param array $params
     * @return void
     */

    public function show($params)
    {
        $id = $params["id"] ?? '';

        $params = [
            "id" => $id
        ];
        $listings = $this->db->query("SELECT * FROM listings  WHERE  id = :id", $params)->fetch();

        //Check iflisting exists
        if (!$listings) {
            ErrorController::notFound("Listing not found");
            return;
        }
        loadView("listings/show", [
            "listings" => $listings
        ]);
    }
    /**
     *Store data in Database
     *   
     *@return void
     */

    public function store()
    {
        $allowFields = ["title", "description", "salary", "requirement", "benefits", "tags", "company", "address", "city", "state", "phone", "email"];

        $newListingData = array_intersect_key($_POST, array_flip($allowFields));
        $newListingData["user_id"] = 1;

        $newListingData = array_map("sanitize", $newListingData);

        $requiredFields = ["title", "description", "salary", "email", "city", "state"];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($newListingData[$field]) || !Validation::string($newListingData[$field])) {
                $errors[$field] = ucfirst($field) . ' is requried';
            }
        }
        if (!empty($errors)) {
            //Reload view with errors
            loadView("listings/create", [
                "errors" => $errors,
                "listings" => $newListingData
            ]);
        } else {
            //Submit Data

            $fields = [];
            foreach ($newListingData as $field => $value) {
                $fields[] = $field;
            }

            $fields = implode(", ", $fields);

            $values = [];

            foreach ($newListingData as $field => $value) {
                //Convert empty string to Null
                if ($value === '') {
                    $newListingData[$field] = null;
                }
                $values[] = ':' . $field;
            }
            $values =  implode(',', $values);

            $query = "INSERT INTO listings({$fields}) VALUES({$values}) ";
            $this->db->query($query, $newListingData);

            redirect("/listings");
        }
    }
    /**
     *Delete a listing
     *
     *@param array $params
     *@return void
     */

    public function destroy($params)
    {
        $id = $params['id'];
        $params = [
            'id' => $id
        ];
        $listings = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        if (!$listings) {
            ErrorController::notFound('Listing not Found');
            return;
        }
        $this->db->query('DELETE FROM listings WHERE id =:id', $params);
        $_SESSION['success_message'] = "Listing Deleted successfuly";

        redirect("/listings");
    }
    /**
     *Show the listing edit form
     *
     *@param array $params
     * @return void
     */

    public function edit($params)
    {
        $id = $params["id"] ?? '';

        $params = [
            "id" => $id
        ];
        $listings = $this->db->query("SELECT * FROM listings  WHERE  id = :id", $params)->fetch();

        //Check iflisting exists
        if (!$listings) {
            ErrorController::notFound("Listing not found");
            return;
        }

        loadView("listings/edit", [
            "listings" => $listings
        ]);
    }
    /**
     *Update listings
     *
     *@param array $params
     *@return void
     */

    public function update($params)
    {
        $id = $params['id'] ?? '';
        $params = [
            "id" => $id
        ];
        $listings = $this->db->query("SELECT * FROM listings  WHERE  id = :id", $params)->fetch();

        //Check iflisting exists
        if (!$listings) {
            ErrorController::notFound("Listing not found");
            return;
        }
        $allowFields = ["title", "description", "salary", "requirement", "benefits", "tags", "company", "address", "city", "state", "phone", "email"];

        $updateValues = [];
        $updateValues = array_intersect_key($_POST, array_flip($allowFields));

        $updateValues = array_map('sanitize', $updateValues);
        $requiredFields = ["title", "description", "salary", "email", "city", "state"];

        $errors = [];
        foreach ($requiredFields as $field) {
            if (empty($updateValues[$field]) || !Validation::string($updateValues[$field])) {
                $errors[$field] = ucfirst($field) . " is Required";
            }
        }
        if (!empty($errors)) {
            loadView("listings/edit", [
                'listings' => $listings,
                'errors' => $errors
            ]);
            exit;
        } else {
            //Submit to database
            $updateFields = [];

            foreach (array_keys($updateValues) as $field) {
                $updateFields[] = "{$field} = :{$field}";
            }

            $updateFields = implode(',', $updateFields);

            $updateQuery = "UPDATE listings  SET $updateFields WHERE id = :id";

            $updateValues['id'] = $id;
            $this->db->query($updateQuery, $updateValues);

            $_SESSION['success_message'] = 'Listing updated';
            redirect("/listings/" . $id);

            inspectAndDie($updateQuery);
        }
    }
}
