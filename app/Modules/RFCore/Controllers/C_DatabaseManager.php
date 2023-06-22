<?php
namespace RFCore\Controllers;

use File;
use RFCore\Models\M_DatabaseManager;
use RFCore\Models\M_Doctrine;

class C_DatabaseManager extends RF_Controller
{
	public function index()
	{
		return render("RFCore\Views\V_DatabaseManager", ['title' => 'Database Manager'], [], LAYOUT_BO);
    }


    /**
     * exports database as .sql (structure or data or both) or .csv format
     *
     * @return \CodeIgniter\HTTP\DownloadResponse|string
     */
    public function exportDbSql()
    {
        $request = $this->request;
        $M_DatabaseManager = new M_DatabaseManager();

        $structure = $this->request->getGet('structure');
        $data = $this->request->getGet('data');

        // check options
        if($structure == 'true' && $data == 'false')
        {
            $M_Doctrine = new M_Doctrine();
            $data = $M_Doctrine->createSchemaSql();
            $fileContent = $data['structure'];

        }
        else if($data == 'true' && $structure == 'false')
        {
            $fileContent = $M_DatabaseManager->exportDataSql();
        }
        else
        {
            $fileContent = $M_DatabaseManager->exportDatabaseSql();
        }

        //create .sql file
        if($fileContent != null)
        {
            date_default_timezone_set('Europe/Paris');
            $data = $this->response->download(config('Database')->default["database"]."_".date("Y-m-d")."_".date("H-i-s").'.sql', $fileContent);
        }
        else
        {
            $data = 'error';
        }

        return $data;
    }

    /**
     * Undocumented function
     *
     * @return bool
     */
    public function exportDbCsv()
    {
        $ret=FALSE;

        $M_DatabaseManager = new M_DatabaseManager();
        $path = $M_DatabaseManager->exportDataCsv();

		if (!$path instanceof \Exception) {

			//set headers, read then delete temp file Europe/Paris
			$this->response->setHeader("Content-Type", "application/zip");
			$this->response->setHeader("Content-Disposition", "filename=" . config('Database')->default["database"] . "_" . date("Y-m-d") . "_" . date("H-i-s") . ".zip");//read from file and output the file on standard stream
			if (readfile($path) != FALSE) {
				$ret = true;
			}
			unlink($path);
		}


		return $ret;
    }

    /**
     * compare objects declaration and database
     *
     * @return array|false|string
     */
    public function validateSchema()
    {
        $M_Doctrine = new M_Doctrine();
        $var = $M_Doctrine->validateSchema();

        $data = ["data" => $var];
        return json_encode($data);
    }

    /**
     * apply objects declarations to database thanks to Doctrine
     *
     * @return array|false|string
     */
    public function updateSchema()
    {
        $M_Doctrine = new M_Doctrine();
        $var = $M_Doctrine->updateSchema();

        $data = ["data" => $var];
        return json_encode($data);
    }

}
?>
