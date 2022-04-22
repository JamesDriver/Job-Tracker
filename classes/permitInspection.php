<?php

function displayPermits($jobId) {
    global $permissions2;
    if (!$permissions2->permitsRead) {
        return NULL;
    }
    $final = "
    <div id='permits'>
        <h4> Permits </h4>";
        if ($permissions2->permitsRequest) {
            $final .= "
            <button class='btn btn-primary' data-toggle='modal' data-target='#permitRequest'>Request</button>";
        }
        if ($permissions2->permitsUpload) {
        $final .= "
        <button class='btn btn-success' data-toggle='modal' data-target='#permitUpload'>Upload</button>";
        }
        $permits = getPermits($jobId);
        if (count($permits) > 0) {
            $final .= '
            <div class="usingMaterials">
                <table class="tableBordered">
                    <thead style="background-color:#999">
                        <tr>
                            <td>Status</td>
                            <td>Details</td>
                            <td>Actions</td>
                        </tr>
                    </thead>
                    <tbody>';
            foreach($permits as $permit) {
                $pending = ($permit['pending'] == 1) ? true : false;
                $status = ($pending) ? '<i>pending</i>' : 'uploaded';
                if ($permit['file']) {
                    $file = getFileById($permit['file']);
                    $actionButton = "<a href='actions/downloadscript?id={$file->getId()}'><button class='btn-success' style='margin-left:10px;float:right;'><span class='fa fa-download'></span></button></a>";
                } elseif ($permissions2->permitsUpload) {
                    $actionButton = "<button class='btn-primary' style='margin-left:10px;float:right;' onclick='editPermit({$permit['id']})'><span class='fa fa-pencil'></span></button>";
                } else {
                    $actionButton = '';
                }
                $final .= "
                    <tr id='permit{$permit['id']}'>
                        <td>
                            {$status}
                        </td>
                        <td id='permitDescription{$permit['id']}'>{$permit['description']}</td>
                        <td>";
                    $final .= "
                        <button class='btn-danger' style='margin-left:10px;float:right;' data-toggle='modal' data-target='#deletePermitModal'  onclick='selectedPermitId={$permit['id']}'><span class='fa fa-trash-o'></span></button>
                    ";
                    if ($permissions2->permitsDelete) {
                        $final .= "{$actionButton}";
                    }
                    $final .= "
                        </td>
                    </tr>";
            }
            $final .= '
                </tbody>
            </table>
        </div>';
    }
    $final .= '</div>';
    return $final;
}

function displayInspections($jobId) {
    global $permissions2;
    if (!$permissions2->inspectionRead) {
        return NULL;
    }
    //statuses:
    //0 = requested
    //1 = pending
    //2 = completed
    $final = "
    <div id='inspections'>
    <h4> Inspections </h4>";
    if ($permissions2->inspectionRequest) {
        $final .= "<button class='btn btn-primary' data-toggle='modal' data-target='#inspectionRequest'>Request</button>";
    }
    if ($permissions2->inspectionUpload) {
        $final .= "<button class='btn btn-success' onclick='inspectionCreateView()'>Create</button>";
    }
    
    $inspections = getInspections($jobId);
    if (count($inspections) > 0) {
        $final .= '
        <div class="usingMaterials">
            <table class="tableBordered">
                <thead style="background-color:#999">
                    <tr>
                        <td>Status</td>
                        <td>Details</td>
                        <td>Date</td>
                        <td>Actions</td>
                    </tr>
                </thead>
                <tbody>
                ';
        foreach($inspections as $inspection) {
            switch($inspection['status']) {
                case 0:
                    $status = 'Requested';
                    break;
                case 1:
                    $status = 'Scheduled';
                    break;
                case 2:
                    $status = 'Pass';
                    break;
                case 3:
                    $status = 'Fail';
                    break;
            }
            if ($inspection['file']) {
                $file = getFileById($inspection['file']);
                $fileName = "file-name='".str_replace("'", "", str_replace('"', '', $file->getName()))."'";
                $actionButton = "<button class='btn-primary' style='margin-left:10px;float:right;' onclick='inspectionUpdateView({$inspection['id']})'><span class='fa fa-pencil'></span></button>
                                 <a href='actions/downloadscript?id={$file->getId()}' id='downloadInspection{$inspection['id']}' {$fileName}><button class='btn-success' style='margin-left:10px;float:right;'><span class='fa fa-download'></span></button></a>
                                 ";
            } elseif ($permissions2->inspectionUpload) {
                $actionButton = "<button class='btn-primary' style='margin-left:10px;float:right;' onclick='inspectionUpdateView({$inspection['id']})'><span class='fa fa-pencil'></span></button>";
            } else {
                $actionButton = "";
            }
            $scheduledDate = '';
            if ($inspection['scheduledDate']) {
                $scheduledDate = "scheduled-date='".date('Y-m-d', strtotime($inspection['scheduledDate'])) . "'";
            }
            $final .= "
                <tr id='inspection{$inspection['id']}'>
                    <td id='inspectionStatus{$inspection['id']}' hidden-data='{$inspection['status']}'>
                        {$status}
                    </td>
                    <td id='inspectionDetails{$inspection['id']}'>{$inspection['details']}</td>
                    <td id='inspectionDates{$inspection['id']}' {$scheduledDate}>";
                    if ($inspection['requestedDate']) { $final .= 'Requested ' .       date('m-d-Y', strtotime($inspection['requestedDate'])); }
                    if ($inspection['scheduledDate']) { $final .= '<br />Scheduled ' . date('m-d-Y', strtotime($inspection['scheduledDate']));   }
                    if ($inspection['completedDate']) { $final .= '<br />Completed ' . date('m-d-Y', strtotime($inspection['completedDate'])); }
                    $final .= "</td>
                    <td>";
                    if ($permissions2->inspectionDelete) {
                        $final .= "<button class='btn-danger' style='margin-left:10px;float:right;' data-toggle='modal' data-target='#deleteInspectionModal' onclick='selectedInspectionId={$inspection['id']}'><span class='fa fa-trash-o'></span></button>";
                    }
                    $final .= $actionButton;
                    $final .= "</td>
                </tr>";
        }
        $final .= '
            </tbody>
        </table>
        </div>';
    }
    $final .= '</div>';
    return $final;
}

function displayPermitsInspections($jobId) {
    global $permissions2;
    $final = '<div id="Permit" class="tabBody" style="text-align:center;">';
    if ($permissions2->permitsRead) {
        $final .= displayPermits($jobId);
    }
    $final .= '<br /><br /><br />';
    if ($permissions2->inspectionRead) {
        $final .= displayInspections($jobId);
    }
    $final .= '</div>';
    return $final;
}

function getPermits($jobId) {
    $stmt = run(sql::$getPermits, array(':job' => $jobId));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getInspections($jobId) {
    $stmt = run(sql::$getInspections, array(':job' => $jobId));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getPermitById($id) {
    global $companyId;
    $stmt = run(sql::$getPermitById, array(':id' => $id));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
function getInspectionById($id) {
    global $companyId;
    $stmt = run(sql::$getInspectionById, array(':id' => $id));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}