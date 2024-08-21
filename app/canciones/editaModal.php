
<!-- Modal nuevo registro -->
<div class="modal fade" id="editaModal" tabindex="-1" aria-labelledby="editaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editaModalLabel">Editar canción</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="actualiza.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id">
                    
                    <div class="row"> <!-- Fila añadida -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tema" class="form-label">Tema:</label>
                                <input type="text" name="tema" id="tema" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="interprete" class="form-label">Intérprete:</label>
                                <input type="text" name="interprete" id="interprete" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="disquera" class="form-label">Disquera:</label>
                                <select name="disquera" id="disquera" class="form-select form-select-sm" required>
                                    <option value="">Seleccionar...</option>
                                    <?php while ($row_disquera = $disqueras->fetch_assoc()) { ?>
                                        <option value="<?php echo $row_disquera["id"]; ?>"><?= $row_disquera["casa"] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="nacionalidad" class="form-label">Nacionalidad:</label>
                                <select name="nacionalidad" id="nacionalidad" class="form-select form-select-sm" required>
                                    <option value="">Seleccionar...</option>
                                    <?php while ($row_nacionalidad = $nacionalidades->fetch_assoc()) { ?>
                                        <option value="<?php echo $row_nacionalidad["id"]; ?>"><?= $row_nacionalidad["pais"] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo:</label>
                                <select name="tipo" id="tipo" class="form-select form-select-sm" required>
                                    <option value="">Seleccionar...</option>
                                    <?php while ($row_tipo = $tipos->fetch_assoc()) { ?>
                                        <option value="<?php echo $row_tipo["id"]; ?>"><?= $row_tipo["estilo"] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-2">
                                <img id="img_caratula" width="75" class="img-thumbnail">
                            </div>
                            <div class="mb-3">
                                <label for="caratula" class="form-label">Carátula:</label>
                                <input type="file" name="caratula" id="caratula" class="form-control form-control-sm" accept="image/jpeg">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="letra" class="form-label">Letra de la canción:</label>
                                <textarea name="letra" id="letra" class="form-control" rows="15" required></textarea>
                            </div>
                        </div>
                    </div> <!-- Fin de la fila añadida -->
                    
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
