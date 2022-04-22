function starJob(job) {
    $.post( "/job/star/", { job: job } )
    .done(function() {
        starAlert(true);
    })
    .fail(function() {
        starAlert(false);
    });
}
function starAlert(success) {
    if (success) {
        //showSuccess();
        //removed because unnecessary
        //fail gives actual info. fail is only required one. success is inferred if no fail.
    } else {
        showFail();
    }
}
function clickStar() {
    document.getElementById('starJob').click();
    document.getElementById('nonStar').click();
}
function clickNonStar() {
    document.getElementById('starJob').click();
    document.getElementById('star').click();
}
function showSuccess() {
    success = document.getElementById('success');
    success.classList.remove('hidden');
}
function hideSuccess() {
    success = document.getElementById('success');
    success.classList.add('hidden');
}
function showFail() {
    fail = document.getElementById('fail');
    fail.classList.remove('hidden');
}
function hideFail() {
    fail = document.getElementById('fail');
    fail.classList.add('hidden');
}
function hideElement(id) {
    document.getElementById(id).classList.add('hidden');
}
function showElement(id) {
    document.getElementById(id).classList.remove('hidden');
}