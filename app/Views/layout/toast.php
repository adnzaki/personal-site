<div class="toast-container position-fixed top-0 p-3" style="width: 100%;">
    <div id="notifKomentar" style="width: 100%;" class="toast align-items-center text-bg-<?= $color ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <?= $message ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>