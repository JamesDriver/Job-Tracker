<?php
require_once '/var/www/globals.php';
require_once '/var/www/classes/status.php';
require_once '/var/www/classes/type.php';

function administrativeSettings() {
    $company = getCompany();
    $statuses   = getStatuses();
    $types      = getTypes();
    $statusFuncs= getStatusFunctions();
    $dailyEmail = $company['dailyEmail'];
    $preJn      = $company['preJobNumber'];
    $permitEmail = $company['permitEmail'];
    $inspectionEmail = $company['inspectionEmail'];
    global $companyId;
    if (isset($_POST['type'])) {
        typePost($_POST);
    }
    if (isset($_POST['status'])) {
        statusPost($_POST);
    }
    if (isset($_POST['workType'])) {
        WorkTypePost($_POST);
    }
    if (isset($_POST['dailyEmail'])) {
        if ($_POST['dailyEmail'] != $dailyEmail) {
            run(sql::$updateDailyEmail, array(':id'=>$companyId, ':email'=>$_POST['dailyEmail']));
        }
    }
    if (isset($_POST['preJn'])) {
        if ($_POST['preJn'] != $preJn) {
            run(sql::$updatePreJobNum, array(':id'=>$companyId, ':preJn'=>$_POST['preJn']));
        }
    }
    if (isset($_POST['permitEmail'])) {
        if ($_POST['permitEmail'] != $permitEmail) {
            run(sql::$updatePermitEmail, array(':id'=>$companyId, ':permitEmail'=>$_POST['permitEmail']));
        }
    }
    if (isset($_POST['inspectionEmail'])) {
        if ($_POST['inspectionEmail'] != $inspectionEmail) {
            run(sql::$updateInspectionEmail, array(':id'=>$companyId, ':inspectionEmail'=>$_POST['inspectionEmail']));
        }
    }

    $company = getCompany();
    $dailyEmail = $company['dailyEmail'];
    $preJn      = $company['preJobNumber'];
    $permitEmail = $company['permitEmail'];
    $inspectionEmail = $company['inspectionEmail'];
    $form = new Form();

    statusSettings($form);
    typeSettings($form);
    workTypeSettings($form);
    jobNumberSettings($form, $preJn);
    dailyEmailSettings($form, $dailyEmail);
    permitInspectionSettings($form, $permitEmail, $inspectionEmail);
    
    $submitRow = new FormRow();
    $submitRow->addField(new FormButton('Save', 'submit', 'btn-primary', NULL, true));
    $form->addRow($submitRow);
    $form->display();
}
function statusPost($datas) {
    $data = $_POST['status'];
    $statuses = getStatuses();
    //deleted
    foreach($statuses as $status) {
        $exists = false;
        $updated = false;
        foreach($data as $key=>$var) {
            if ($key == $status->getId()) {
                $exists = true;
                if ($var != $status->getName() || $datas['statusColor'][$key] != $status->getColor() || $datas['statusFunction'][$key] != $status->getFunction() || $datas['statusEmail'][$key] != $status->getEmail()) {
                    $updated = true;             
                }
                break;
            }
        }
        if (!$exists) {
            if ($status->getFunction() != 2 && $status->getFunction() != 3) {
                $status->delete();
            }
        }
        if ($updated) {
            $status->setName(    $var                          );
            $status->setColor(   $datas['statusColor'][$key]   );
            $status->setEmail(   $datas['statusEmail'][$key]   );
            $status->setFunction($datas['statusFunction'][$key]);
            $status->update();
        }
    }
    foreach($data as $key=>$var) {
        $exists = false;
        foreach($statuses as $status) {
            if ($key == $status->getId()) {
                $exists = true;
            }
        }
        if (!$exists) {
            $status = new status();
            $status->setName($var);
            $status->setColor($datas['statusColor'][$key]);
            $status->setFunction($datas['statusFunction'][$key]);
            if ($datas['statusEmail'][$key]) {
                $status->setEmail($datas['statusEmail'][$key]);
            }
            $status->create();
        }
        else {
        }
    }
}
function typePost($datas) {
    $data = $_POST['type'];
    $types = getTypes();
    if (count($data) < 1) {
        log::error(errors::$atLeastOneTypeRequired);
    }
    foreach($types as $type) {
        $exists = false;
        $updated = false;
        foreach ($data as $key=>$var) {
            if ($key == $type->getId()) {
                $exists = true;
                if ($var != $type->getName() || $datas['typeColor'][$key] != $type->getColor()) {
                    $updated = true;
                }
                break;
            }
        }
        if (!$exists) {
            $type->delete();
        } else {
        }
        if ($updated) {
            $type->setName($var);
            $type->setColor($datas['typeColor'][$key]);
            $type->update();
        }
    }
    foreach($data as $key=>$var) {
        $exists = false;
        foreach($types as $type) {
            if ($key == $type->getId()) {
                $exists = true;
            }
        }
        if (!$exists) {
            $type = new Type();
            $type->setName($var);
            $type->setColor($datas['typeColor'][$key]);
            $type->create();
        }
    }
}






function workTypePost($datas) {
    $data = $_POST['workType'];
    $workTypes = getWorkTypes();
    if (count($data) < 1) {
        log::error(errors::$atLeastOneWorkTypeRequired);
    }
    //deleted
    foreach($workTypes as $wt) {
        $exists = false;
        $updated = false;
        foreach ($data as $key=>$var) {
            if ($key == $wt->getId()) {
                $exists = true;
                if ($var != $wt->getName()) {
                    $updated = true;
                }
                break;
            }
        }
        if (!$exists) {
            $wt->delete();
        }
        if ($updated) {
            $wt->setName($var);
            $wt->update();
        }
    }
    //created
    foreach($data as $key=>$var) {
        $exists = false;
        foreach($workTypes as $wt) {
            if ($key == $wt->getId()) {
                $exists = true;
            }
        }
        if (!$exists) {
            $wt = new WorkType();
            $wt->setName($var);
            $wt->create();
        }
    }
}




function permitInspectionSettings(&$form, $permit, $inspection) {
    $permitLabel     = new FormRow();
    $inspectionLabel = new FormRow();
    $permitRow       = new FormRow();
    $inspectionRow   = new FormRow();
    $permitLabel->addField(    new BigLabel('Permit Request Email'));
    $inspectionLabel->addField(new BigLabel('Inspection Request Email'));
    $permitRow->addField(      new FormTextInput('', 'permitEmail', $permit, 200, '', ''));
    $inspectionRow->addField(  new FormTextInput('', 'inspectionEmail', $inspection, 200, '', ''));
    $form->addRow($permitLabel);
    $form->addRow($permitRow);
    $form->addRow($inspectionLabel);
    $form->addRow($inspectionRow);
    return $form;
}

function dailyEmailSettings(&$form, $dailyEmail) {
    $dailyEmailLableRow = new FormRow();
    $dailyEmailRow = new FormRow();
    $dailyEmailLableRow->addField(new BigLabel('Daily Email'));
    $form->addRow($dailyEmailLableRow);
    $dailyEmailRow->addField(new FormTextInput('', 'dailyEmail', $dailyEmail, 50, '', ''));
    $form->addRow($dailyEmailRow);
    return $form;
}
function jobNumberSettings(&$form, $preJn) {
    $jnLableRow = new FormRow();
    $jnRow = new FormRow();
    $jnLableRow->addField(new BigLabel('Prepend Job Number'));
    $form->addRow($jnLableRow);
    $jnRow->addField(new FormTextInput('', 'preJn', $preJn, 5, '', ''));
    $form->addRow($jnRow);
    return $form;
}
function typeSettings(&$form) {
    $typeLableRow = new FormRow();
    $typeLableRow->addField(new BigLabel('Job Types'));
    $form->addRow($typeLableRow);
    $types = getTypes();
    
    foreach($types as $type) {
        $typeRow = new FormRow();
        $typeRow->style('');
        $typeRow->setId('type'.$type->getId());
        $typeRow->addField(new TypeInput($type));
        $form->addRow($typeRow);
    }
    $typeAddRow = new FormRow(); 
    $typeAddRow->setId('typeAddRow');
    $typeAddRow->addField(new TypeAdd());
    $form->addRow($typeAddRow);
    return $form;
}
function statusSettings(&$form) {
    $count = 0;
    $statusLableRow = new FormRow();
    $statusLableRow->addField(new BigLabel('Job Statuses'));
    $form->addRow($statusLableRow);
    $statuses   = getStatuses();
    $statusFuncs = getStatusFunctions();

    foreach($statuses as $status) {
        $statusRow = new FormRow();
        $statusRow->style('');
        $statusRow->setId('status'.$status->getId());
        $statusRow->addField(new StatusInput($status, $statusFuncs, $status->getId()));
        $form->addRow($statusRow);  
    }

    $statusAddRow = new FormRow(); 
    $statusAddRow->setId('statusAddRow');
    $statusAddRow->addField(new StatusAdd());
    $form->addRow($statusAddRow);
    return $form;
}

function workTypeSettings(&$form) {
    $wtLableRow = new FormRow();
    $wtLableRow->addField(new BigLabel('Work Types'));
    $form->addRow($wtLableRow);
    $workTypes = getWorktypes();

    foreach($workTypes as $workType) {
        $wtRow = new FormRow();
        $wtRow->style('');
        $wtRow->setId('workType'.$workType->getId());
        $wtRow->addField(new workTypeInput($workType));
        $form->addRow($wtRow);  
    }
    $workTypeAddRow = new FormRow(); 
    $workTypeAddRow->setId('WorkTypeAddRow');
    $workTypeAddRow->addField(new WorkTypeAdd());
    $form->addRow($workTypeAddRow);
    return $form;
}

//placeholder, for future reference
function userSettings() {
    
}