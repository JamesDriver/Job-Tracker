<?php
/*
          _                 _   
    /\   | |               | |  
   /  \  | |__   ___  _   _| |_ 
  / /\ \ | '_ \ / _ \| | | | __|
 / ____ \| |_) | (_) | |_| | |_ 
/_/    \_\_.__/ \___/ \__,_|\__|
JOB MATERIALS:
job materials are the data model for jobs to have materials attributed to them
for example: a job can have 300 couplings attributed to it this way


ORGANIZATION:
There are two types of job materials: normal and custom.

Normal materials exist to reference between materials that exist within the
'materials' table, and the job, so that any changes to those materials can be attributed to the job

Custom materials exist as a catch-all for any materials that don't exist in the material table
they give the accounting department a layer that only they can control in "materials".
Without 'custom', any material that was searched could be created by the user, leading to 
unnecessary bloat and incorrect data/difficulty updating material price.


 __  __       _          _____ _                         
|  \/  |     (_)        / ____| |                        
| \  / | __ _ _ _ __   | |    | | __ _ ___ ___  ___  ___ 
| |\/| |/ _` | | '_ \  | |    | |/ _` / __/ __|/ _ \/ __|
| |  | | (_| | | | | | | |____| | (_| \__ \__ \  __/\__ \
|_|  |_|\__,_|_|_| |_|  \_____|_|\__,_|___/___/\___||___/
jobMaterial interface 
-JobNormalMaterial       Holds all normal materials(already having existing material)
-JobCustomMaterial       Holds all custom materials(those without existing material)

 ______         _             _           
|  ____|       | |           (_)          
| |__ __ _  ___| |_ ___  _ __ _  ___  ___ 
|  __/ _` |/ __| __/ _ \| '__| |/ _ \/ __|
| | | (_| | (__| || (_) | |  | |  __/\__ \
|_|  \__,_|\___|\__\___/|_|  |_|\___||___/
-JobMaterialFactory       Constructs jobMaterials from db
-JobMaterialPostFactory   Handles post from jobMaterials page

 ______                _   _                 
|  ____|              | | (_)                
| |__ _   _ _ __   ___| |_ _  ___  _ __  ___ 
|  __| | | | '_ \ / __| __| |/ _ \| '_ \/ __|
| |  | |_| | | | | (__| |_| | (_) | | | \__ \
|_|   \__,_|_| |_|\___|\__|_|\___/|_| |_|___/
-getMaterialsByJob        returns all materials on a job
-handleJobMaterialPost    passes posted data to JobMaterialPostFactory
-displayJobMaterials      displays the template for jobMaterials
*/
require_once '/var/www/classes/material.php';
interface jobMaterial {
    public function isCustom();
    public function getId();
    public function getJob();
    public function getPrice();
    public function getPhoto();
    public function getName();

    public function setId($var);
    public function setJob($var);
    public function setPrice($var);
    public function setPhoto($var);
    public function setName($var);

    public function create();
    /*
    DELETE:
    Not handled here because no need to individual delete.
    Only need to individually create, then group delete on 
    update and re-fill with creates
    */
}

class JobNormalMaterial implements jobMaterial {
    private $id;
    private $job;
    private $material;
    private $inventory = 0;
    private $non_inventory = 0;
    private $price = 0;
    private $photo = NULL;
    private $materialId;

    public function isCustom(  )  { return false;           }
    public function getId(      ) { return $this->id;       }
    public function getJob(     ) { return $this->job;      }
    public function getMaterial() { return $this->material; }
    public function getName(    ) { return $this->name;     }
    public function getPrice(   ) { return $this->price;    }
    public function getPhoto(   ) { return $this->photo;    }
    public function getInventory() { return $this->inventory;}
    public function getNonInventory() { return $this->non_inventory; }
    public function getMaterialId() { return $this->materialId; }

    public function setId(          $var) { $this->id            = $var; }
    public function setJob(         $var) { $this->job           = $var; }
    public function setInventory(   $var) { $this->inventory     = $var; }
    public function setNonInventory($var) { $this->non_inventory = $var; }
    public function setPrice(       $var) { $this->price         = $var; }
    public function setPhoto(       $var) { 
        if (is_numeric($var)) {
            $this->photo = getFileById($var);
        } else {
            $this->photo = $var;
        }
    }
    public function setName(        $var) { $this->name          = $var; }
    public function setMaterialId(  $var) { $this->materialId    = $var; }
    public function setMaterial($var) {
        if (is_numeric($var)) {
            $var = getMaterialById($var);
        }
        $this->material = $var;
        $this->setName($var->getDescription());
        $this->setMaterialId($var->getId());
    }

    public function create() {
        $parameters = array(
            ':job'          => $this->getJob(),
            ':material'     => $this->getMaterialId(),
            ':inventory'    => $this->getInventory(),
            ':nonInventory' => $this->getNonInventory(),
            ':price'        => $this->getPrice(),
            ':photo'        => (method_exists($this->getPhoto(), 'getId')) ? $this->getPhoto()->getId() : null
        );
        if (run(sql::$createJobNormalMaterial, $parameters)) {
            return true;
        } else {
            return false;
        }
        //run(sql::createNewJobNormalMaterial);
    }
}

class JobCustomMaterial implements jobMaterial {
    private $id;
    private $job;
    private $name;
    private $quantity = 0;
    private $price = 0;
    private $photo = NULL;

    public function isCustom(   ) { return true;           }
    public function getId(      ) { return $this->id;       }
    public function getJob(     ) { return $this->job;      }
    public function getName(    ) { return $this->name;     }
    public function getPrice(   ) { return $this->price;    }
    public function getPhoto(   ) { return $this->photo;    }
    public function getQuantity() { return $this->quantity; }

    public function setId(          $var) { $this->id            = $var; }
    public function setJob(         $var) { $this->job           = $var; }
    public function setQuantity(    $var) { $this->quantity      = $var; }
    public function setPrice(       $var) { $this->price         = $var; }
    public function setPhoto(       $var) { 
        if (is_numeric($var)) {
            $this->photo = getFileById($var);
        } else {
            $this->photo = $var;
        }
    }
    public function setName(        $var) { $this->name          = $var; }
    public function setMaterial($var) {
        $this->material = $var;
        $this->setName($material->getDescription());
    }

    public function create() {
        $parameters = array(
            ':job'      => $this->getJob(),
            ':name'     => $this->getName(),
            ':quantity' => $this->getQuantity(),
            ':price'    => $this->getPrice(),
            ':photo'    => (method_exists($this->getPhoto(), 'getId')) ? $this->getPhoto()->getId() : null
        );
        if (run(sql::$createJobCustomMaterial, $parameters)) {
            return true;
        } else {
            return false;
        }
    }
}

/*
 ______         _             _           
|  ____|       | |           (_)          
| |__ __ _  ___| |_ ___  _ __ _  ___  ___ 
|  __/ _` |/ __| __/ _ \| '__| |/ _ \/ __|
| | | (_| | (__| || (_) | |  | |  __/\__ \
|_|  \__,_|\___|\__\___/|_|  |_|\___||___/

*/

class JobMaterialFactory {
    private $materials = array();
    private $jobMaterials = array();
    private $job;
    public function getMaterials($job) {
        //since you can't overload functions in php, have to do this
        if (is_a($job, 'Job')) {
            $jobId = $job->getId();
            $this->job = $job;
        } elseif (is_numeric($job)) {
            $jobId = $job;
        } else {
            return false;
        }
        $parameters = array(':job' => $jobId);
        $returns    = run(sql::$getJobCustomMaterials, $parameters);
        $returns2   = run(sql::$getJobNormalMaterials, $parameters);
        //sql
        $db_response = array_merge(
            $returns2->fetchAll(PDO::FETCH_ASSOC),
            $returns->fetchAll(PDO::FETCH_ASSOC)
        );
        return $this->constructMaterials($db_response);
    }

    private function constructMaterials($datas) {
        $materials = array();
        foreach($datas as $data) {
            if (isset($data['quantity'])) {
                $jobMaterial = $this->constructJobCustomMaterial($data);
            } else {
                $jobMaterial = $this->constructJobNormalMaterial($data);
            }
            array_push($this->jobMaterials, $jobMaterial);
        }
        return $this->jobMaterials;
    }

    private function constructJobCustomMaterial($data) {
        $jobMaterial = new JobCustomMaterial();
        $jobMaterial->setId($data['id']);
        $jobMaterial->setJob($data['job']);
        $jobMaterial->setName($data['name']);
        $jobMaterial->setQuantity($data['quantity']);
        $jobMaterial->setPrice($data['price']);
        $jobMaterial->setPhoto($data['photo']);
        return $jobMaterial;
    }
    private function constructJobNormalMaterial($data) {
        if (!$this->materials) {
            $tmpMaterials = getMaterials();
            foreach($tmpMaterials as $tmpMaterial) {
                $this->materials[$tmpMaterial->getId()] = $tmpMaterial;
            }
        }
        $jobMaterial = new JobNormalMaterial();
        $material = $this->materials[$data['material']];
        $jobMaterial->setId(          $data['id']          );
        $jobMaterial->setJob(         $data['job']         );
        $jobMaterial->setInventory(   $data['inventory']   );
        $jobMaterial->setNonInventory($data['nonInventory']);
        $jobMaterial->setPrice(       $data['price']       );
        $jobMaterial->setPhoto(       $data['photo']       );
        $jobMaterial->setMaterial(    $material            );
        return $jobMaterial;
    }
}


class JobMaterialPostFactory {
    /*
    ABOUT:
        this class exists in order to handle jobmaterial data posted from 
        a job page regarding materials. It deletes existing jobmaterials, 
        updates material inventories, creates jobmaterials from posted data,
        then updates material inventories.

    VARIABLES:
        existingJobMaterials    materials that already exist for given job
        newJobMaterials         materials that were posted to the job
        job                     referenced job                  
        jobId                   referenced jobid    
    FUNCTIONS:
        __CONSTRUCT                  data construction function
        updateJobMaterials           data handling function
        classify_materials           turn posted data into its relative jobMaterial classes
        updateExistingInventory      update material inventories for all existing jobmaterials
        updateNewInventory           update material inventories for all posted jobmaterials
        deleteCurrentJobMaterials    delete all jobMaterials for given job
    */
    private $existingJobMaterials = array();
    private $newJobMaterials = array();
    private $job;
    private $jobId;

    public function __CONSTRUCT($data) {
        //start process of handling job material posts
        //eventually need to return a true or something 
        $this->jobId = $data['job'];
        $this->job = getJobById($this->jobId);
        $this->classify_materials(json_decode($data['materials']));
        $this->existingJobMaterials = getMaterialsByJob($this->jobId);
        return $this->updateJobMaterials();
    }

    private function updateJobMaterials() {
        //run all necessary functions to update jobMaterials, then return t/f
            $this->updateExistingInventory();//   &&
            $this->deleteCurrentJobMaterials(); //&&
            $this->createNewJobMaterials(); //  ) {//     &&
            $this->updateNewInventory();// {
            return true;
       // } 
        return false;
    }

    private function classify_materials( $decoded_data ) {
        //construct post data into jobMaterial class instances
        foreach($decoded_data as $tmp) {
            if (isset($tmp->{'id'})) {
                $jobMaterial = new jobNormalMaterial( );
                $jobMaterial->setJob(          $this->jobId                    );
                $jobMaterial->setPrice(        $tmp->{'price'}                 );
                $jobMaterial->setMaterial(     getMaterialById( $tmp->{'id'} ) );
                $jobMaterial->setInventory(    $tmp->{'inventory'}             );
                $jobMaterial->setNonInventory( $tmp->{'non_inventory'}         );
                $jobMaterial->setPhoto(        $tmp->{'photo'}                 );
            } else {
                $jobMaterial = new jobCustomMaterial();
                $jobMaterial->setJob($this->jobId);
                $jobMaterial->setName($tmp->{'name'});
                $jobMaterial->setQuantity($tmp->{'quantity'});
                $jobMaterial->setPrice($tmp->{'price'});
                $jobMaterial->setPhoto($tmp->{'photo'});
            }
            array_push($this->newJobMaterials, $jobMaterial);
        }
    }

    private function updateExistingInventory() {
        //update material inventory for soon-to-be-deleted jobmaterials
        foreach($this->existingJobMaterials as $jobMaterial) {
            //reset inventory quantity etc
            if (!$jobMaterial->isCustom()) {
                $material = $jobMaterial->getMaterial();
                $tmpInvt = $material->getInventory();
                $material->setInventory($tmpInvt + $jobMaterial->getInventory());
                $material->update();
            }
        }
    }

    private function updateNewInventory() {
        //update material inventory for newly created jobmaterials
        foreach($this->newJobMaterials as $jobMaterial) {
            if (!$jobMaterial->isCustom()) {
                $material = getMaterialById($jobMaterial->getMaterialId());
                $tmpInvt = $material->getInventory();
                $material->setInventory($tmpInvt - $jobMaterial->getInventory());
                $material->update();
            }
        }
    }

    private function createNewJobMaterials() {
        foreach($this->newJobMaterials as $jobMaterial) {
            $jobMaterial->create();
        }
    }

    private function deleteCurrentJobMaterials() {
        //run(sql::$deleteCurrentJobMaterials())
        run(sql::$deleteJobCustomMaterials, array(':jobId' => $this->jobId));
        run(sql::$deleteJobNormalMaterials, array(':jobId' => $this->jobId));
    }

}

/*
 ______                _   _                 
|  ____|              | | (_)                
| |__ _   _ _ __   ___| |_ _  ___  _ __  ___ 
|  __| | | | '_ \ / __| __| |/ _ \| '_ \/ __|
| |  | |_| | | | | (__| |_| | (_) | | | \__ \
|_|   \__,_|_| |_|\___|\__|_|\___/|_| |_|___/

*/

function getMaterialsByJob($job) {
    return (new JobMaterialFactory())->getMaterials($job);
}

function handleJobMaterialPost($data) {
    new JobMaterialPostFactory($data);
    displayJobMaterials($data['job']);
}

function displayJobMaterials($jobId = NULL) {
    global $permissions2;
    if ($jobId) {
        $jobMaterials = (new JobMaterialFactory())->getMaterials($jobId);
    } else {
        return false;
    }
    $job = getJobById($jobId);
    include '/var/www/html/templates/jobMaterial.php';
}