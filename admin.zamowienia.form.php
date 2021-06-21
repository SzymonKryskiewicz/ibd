<form method="post" action="" enctype="multipart/form-data" id="<?=empty($id) ? 'fDodajKsiazke' : '' ?>">
    <div class="form-group">
        <label for="id_statusu">Status</label>
        <select name="id_statusu" id="id_statusu" class="form-control <?= $v->errors('id_statusu') ? 'is-invalid' : '' ?>">
            <?php foreach ($listaStatusow as $stat) : ?>
                <option value="<?= $stat['id'] ?>" <?= ($dane['id_statusu'] ?? '') == $stat['id'] ? 'selected="selected"' : '' ?>><?= $stat['nazwa'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Zapisz</button>

    <?php if (!empty($id)): ?>
        <a href="admin.zamowienia.lista.php" class="btn btn-link">powrót</a>
    <?php endif; ?>

    <hr />
</form>