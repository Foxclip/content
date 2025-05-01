<button <?= isset($id) ? "id=\"{$id}\"" : '' ?> type="submit" class="submitButton">
    <span><?=$text?></span>
    <img src="/icons/send.png" width="20" height="20">
</button>
