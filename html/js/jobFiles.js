class JobFileHandler {
    constructor(fileInput, filesUl, fileEditModal) {
        this.fileEditModal = fileEditModal;
        this.constructOldFiles(filesUl);
        this.constructEventListener(fileInput);
    }

    constructOldFiles(filesUl) {
        let filesLiArr = filesUl.getElementsByTagName("li");
        this.JobFilesOld = [];
        for (let item of filesLiArr) {
            this.JobFilesOld.push(new JobFileOld(item, this.fileEditModal));
        };
    }
    constructEventListener(fileInput) {
        $(fileInput).change(function(e){
            var filesSelected = e.target.files;
            for (let i = 0; i < filesSelected.length; i++){
                $.post( "/file/jobFile/", { name: filesSelected[i].name } )
                    .done(function(data) {
                        $('#jobFileList').append(data);
                    })
                    .fail(function() {
                        starAlert(false);
                    }
                );
            }
        });
    }
    
}

class JobFileNew {
    constructor(jobFileLi) {

        //this.name = jobFileLi.querySelector('.fileName');
        //this.id;
        //this.permissions;
    }
    delete() {
        //ajax post
        //remove file from list
    }
}

class JobFileOld {
    constructor(fileLi, modal) {
        this.fileLi = fileLi
        this.modal = modal;
        this.json = JSON.parse(fileLi.querySelector('.json').value);
        this.name = this.json['name'];
        this.id = this.json['id'];
        this.permissions;
        $(fileLi.querySelector('.editFile')).click(() => { this.fillModal(); });
        $(this.modal.getSave()).click(() => { this.save() });
    }
    delete() {
        //remove file from list
    }
    fillModal() {
        this.modal.setId(this.id);
        this.modal.setName(this.name);
        this.modal.setPermissions(this.json['permissions']);
    }
    save() {
        this.setJson();
        this.setName();
        this.setPermissions();
    }
    setJson() {
        let final = "{";
        final += this.modal.getIdJson() + ",";
        final += this.modal.getNameJson() + ",";
        final += this.modal.getPermissionsJson();
        final += '}';
        this.fileLi.querySelector('.json').value = final;
        this.json = JSON.parse(final);
        this.name = this.json['name'];
        this.id = this.json['id'];
    }
    setName() {
        this.fileLi.querySelector('.fileName').innerHTML = this.modal.getName();
    }
    setPermissions() {
        let permissions = this.modal.getPermissions();
        for (var permission of permissions) {
            let current = this.fileLi.querySelector('.userIcon' + permission['id']);
            if (permission['value']) {
                current.classList.remove('hidden');
            } else {
                current.classList.add('hidden');
            }
        }
    }
}

class FileEditModal {
    constructor(modal) {
        this.modal = modal
        this.id   = modal.querySelector('.fileId');
        this.name = modal.querySelector('.fileName');
        this.ext  = modal.querySelector('.fileExt');
        this.save = modal.querySelector('.fileSave');
    }
    setId(id) {
        this.id.value=id;
    }
    setName(fileName) {
        let fn    = fileName.split('.');
        let fnExt = fn.pop();
        this.name.value    = fn.join('');
        this.ext.innerHTML = '.' + fnExt;
    }
    setPermissions(permissions) {
        this.permissions = permissions;
        for (var permission of permissions) {
            let current = this.modal.querySelector('.userType'+permission['id']);
            current.checked = permission['value'];
        }
        //modal.querySelector('userType'.permissions[i]).checked = permissions[i];
    }
    getId()     { return this.id.value; }
    getIdJson() { return '"id": "' + this.id.value + '"'; }

    getName()     { return this.name.value + this.ext.innerHTML; }
    getNameJson() { return '"name": "' + this.name.value + this.ext.innerHTML + '"'; }

    getPermissionsJson() {
        let items = this.modal.querySelectorAll('.permissionItems');
        let permissionStr = '"permissions": ['
        for (let i = 0; i < items.length; i++) {
            let val = items[i].querySelector('.userType').checked;
            let id  = items[i].querySelector('.id').value;
            permissionStr += '{"id":"' + id + '",';
            permissionStr += '"value":' + val + '}';
            if (i+1 != items.length) {
                permissionStr += ',';
            }
        }
        permissionStr += ']'
        return permissionStr;
    }
    getPermissions() {
        return this.permissions;
    }
    getSave() {
        return this.save;
    }

}
