class Table {
    constructor(outer, tableClass) {
        //this.table must be created
        this.outer = outer;
        this.table = outer.querySelector(tableClass);
        this.itemsPerPage = ($(this.table).attr('itemsPerPage')) ? $(this.table).attr('itemsPerPage') : 100;
        this.totalItems = $(this.table).attr('itemCount');
        this.pageNumber = parseInt($(this.table).attr('page'));
        this.tableRows = this.table.rows.length;
        this.totalPages = Math.ceil(this.totalItems / this.itemsPerPage);
    }
}
class PaginatedTable extends Table {
    constructor(outer, tableClass) {
        super(outer, tableClass);
        this.pagination = outer.querySelector('.pagination');
        this.start = this.pagination.querySelector('.startNum');
        this.end   = this.pagination.querySelector('.endNum');
        this.total = this.pagination.querySelector('.totalNum');    
        this.numberShowing();
        this.pageSelector();
        this.setTriggers();
    }

    nextPage() {
        if ( (this.pageNumber + 1) < this.totalPages ) {
            this.goToPage(this.pageNumber + 1);

        }
    }
    prevPage() {
        if ( this.pageNumber - 1 >= 0 ) {
            this.goToPage(this.pageNumber - 1)
        }
    }
    setStart(amt) {
        this.start.innerHTML = amt;
    }
    setEnd(amt) {
        if (amt > this.totalItems) { amt = this.totalItems; }
        this.end.innerHTML = amt;
    }
    setTotal(amt) {
        this.total.innerHTML = amt;
    }
    numberShowing() {
        //assign the numbers in the "showing x to y out of z" part of pagination
        this.setStart(parseInt(this.pageNumber) * 100 + 1);
        this.setEnd(parseInt(this.pageNumber) * 100 + 100);
        this.setTotal(parseInt(this.totalItems));
    }
    pageSelector() {
        let numberings = [
            this.pagination.querySelector('.page1'),
            this.pagination.querySelector('.page2'),
            this.pagination.querySelector('.page3'),
            this.pagination.querySelector('.page4'),
            this.pagination.querySelector('.page5'),
            this.pagination.querySelector('.page6'),
            this.pagination.querySelector('.page7')
        ];
        let i;
        if (this.totalPages <= 7) {
            for (i = 0; i < numberings.length; i++) {
                if (i+1 > this.totalPages) {
                    numberings[i].style.display='none';
                    continue;
                } else {
                    numberings[i].innerHTML = i+1;
                }
                if (this.pageNumber == i) {
                    $(numberings[i]).addClass('bg-gray-200')
                }
            }
        } else if (((this.pageNumber+1) > 2) && (this.pageNumber+1) < (this.totalPages-1)) {
            numberings[0].innerHTML = '1'
            numberings[1].innerHTML = '...';
            numberings[2].innerHTML = parseInt(this.pageNumber);
            numberings[3].innerHTML = parseInt(this.pageNumber) + 1;
            numberings[4].innerHTML = parseInt(this.pageNumber) + 2;
            numberings[5].innerHTML = '...';
            numberings[6].innerHTML = this.totalPages;
            $(numberings[3]).addClass('bg-gray-200')
        } else {
            for (i = 0; i < numberings.length; i++) {
                if (i > 3) {
                    numberings[i].innerHTML = (this.totalPages - (numberings.length-1 - (i)));
                } else {
                    numberings[i].innerHTML = i+1;
                }
                if (numberings[i].innerHTML == this.pageNumber + 1) {
                    $(numberings[i]).addClass('bg-gray-200')
                }
            }
            numberings[3].innerHTML = '...';

        }
    }
    setTriggers() {
        let prevPageSmall = this.pagination.querySelector('.prevPageSmall');
        let prevPage = this.pagination.querySelector('.prevPage');

        let page1 = this.pagination.querySelector('.page1');
        let page2 = this.pagination.querySelector('.page2');
        let page3 = this.pagination.querySelector('.page3');
        let page4 = this.pagination.querySelector('.page4');
        let page5 = this.pagination.querySelector('.page5');
        let page6 = this.pagination.querySelector('.page6');
        let page7 = this.pagination.querySelector('.page7');

        let nextPageSmall = this.pagination.querySelector('.nextPageSmall');
        let nextPage      = this.pagination.querySelector('.nextPage');

        $(prevPageSmall).click(() => { this.prevPage(); });
        $(prevPage     ).click(() => { this.prevPage(); });
           
        $(page1).click(() => { this.goToPage(page1.innerHTML-1) });
        $(page2).click(() => { this.goToPage(page2.innerHTML-1) });
        $(page3).click(() => { this.goToPage(page3.innerHTML-1) });
        $(page4).click(() => { this.goToPage(page4.innerHTML-1) });
        $(page5).click(() => { this.goToPage(page5.innerHTML-1) });
        $(page6).click(() => { this.goToPage(page6.innerHTML-1) });
        $(page7).click(() => { this.goToPage(page7.innerHTML-1) });

        $(nextPageSmall).click(() => { this.nextPage(); });
        $(nextPage     ).click(() => { this.nextPage(); });
    }
}
class JobTable extends PaginatedTable {
    constructor(outer) {
        super(outer, '.jobTable');
        this.outer = outer;
    }
    sortMe() {
        this.goToPage(0);
    }
    setSort(obj) {
        this.sort = obj;
        $(this.sort.getSubmitButton()).click(() => { 
            this.sort.refreshValues();
            this.sortMe()
        });
    }
    goToPage(pageNum) {
        this.outer.style.display='none';
        $.post( "/table/jobs", { 
            page: parseInt(pageNum), 
            statuses: this.sort.statuses,
            types: this.sort.types,
            workers: this.sort.workers   })
        .done((data) => {
            this.outer.parentElement.innerHTML = data;
            constructJobTable();
        });
    }
}

class DailyTable extends PaginatedTable {
    constructor(outer) {
        super(outer, '.dailyTable');
        this.outer = outer;
    }
    goToPage(pageNum) {
        this.outer.style.display='none';
        $.post( "/table/daily", { 
            page: parseInt(pageNum)
        })
        .done((data) => {
            this.outer.parentElement.innerHTML = data;
            constructDailyTable();
        });
    }
}


class Sort {

}

class JobSort extends Sort {
    #statusInput;
    #typeInput;
    #workerInput;
    #submitButton;
    constructor(document, inputs) {
        super();
        this.#statusInput = document.getElementById('statusSort');
        this.#typeInput   = document.getElementById('typeSort');
        this.#workerInput = document.getElementById('workerSort');
        this.#submitButton = document.getElementById('jobSortSubmit');
        this.statuses = [];
        this.types = [];
        this.workers = [];
    }
    refreshValues() {
        let statusData = $(this.#statusInput).select2('data');
        let typeData   = $(this.#typeInput).select2('data');
        let workerData = $(this.#workerInput).select2('data');

        for (let i = 0; i < statusData.length; i++) { this.statuses.push(statusData[i].id); }
        for (let i = 0; i < typeData.length;   i++) { this.types.push(   typeData[i].id);   }
        for (let i = 0; i < workerData.length; i++) { this.workers.push( workerData[i].id); }
        
    }
    getSubmitButton() {
        return this.#submitButton;
    }
}

//needed function::
/*
change page
ajax next page



*/