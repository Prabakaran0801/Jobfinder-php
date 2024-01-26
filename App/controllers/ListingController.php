<?php

namespace App\Controllers;

use Framework\Database;

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
     *  @return void
     */

    public function show()
    {
        $id = $_GET["id"] ?? '';

        $params = [
            "id" => $id
        ];
        $listings = $this->db->query("SELECT * FROM listings  WHERE  id = :id", $params)->fetch();

        loadView("listings/show", [
            "listings" => $listings
        ]);
    }
}
