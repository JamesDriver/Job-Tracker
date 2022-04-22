$(document).ready(function() {
    $('#managers').select2();
    $('#client').select2();
    $('#workers').select2();
    $('#dispatchWorkers').select2();
});

function dispatchJob(job) {
    displayDispatchStatus();
    $.post( "/job/dispatch/", { job: job } )
        .done(function() {
            dispatchStatusSuccess();
            window.setTimeout(testMe, 3000);

        })
        .fail(function() {
            dispatchStatusFail();
        });
}
function testMe() {
    console.log('here');
    document.getElementById('dispatchStatusSuccess').style.display='none'
}
function displayDispatchStatus() {
    document.getElementById('dispatchModal').style.display='none';
    document.getElementById('dispatchStatus').style.display='block';
}
function dispatchStatusFail() {
    document.getElementById('dispatchStatus').style.display='none';
    document.getElementById('dispatchStatusFail').style.display='block';
}
function dispatchStatusSuccess() {
    document.getElementById('dispatchStatus').style.display='none';
    document.getElementById('dispatchStatusSuccess').style.display='block';
}