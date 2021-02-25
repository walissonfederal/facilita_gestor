<div id="_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><div id="title_modal"></div></h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <p>
                <div id="texto_modal"></div>
                </p>
                <p>
                <div id="texto_modal_p"></div>
                </p>
            </div>
            <!-- /.modal-body -->
            <div class="modal-footer">
                <div id="buttons_modal"></div>
            </div>
            <!-- /.modal-footer -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /#modal-1.modal fade -->
<div id="_modal_mail" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Enviar E-Mail</h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <label>E-Mail Destino</label>
                <input type="text" class="form-control txt_email"/>
                <label>Observação do E-Mail</label>
                <textarea class="form-control txt_obs"></textarea>
                <input type="hidden" class="_spc_id"/>
            </div>
            <!-- /.modal-body -->
            <div class="modal-footer">
                <div id="resposta_mail"></div>
                <button type="button" class="btn btn-default" onclick="send_mail();">Enviar E-Mail</button>
            </div>
            <!-- /.modal-footer -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /#modal-1.modal fade -->