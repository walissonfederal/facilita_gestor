<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Empresa
                            </h3>
                        </div>
                        <div class="box-content">
                            <ul class="tabs tabs-inline tabs-top">
                                <li class="active">
                                    <a href="#t_principal" data-toggle="tab">PRINCIPAL</a>
                                </li>
                                <li>
                                    <a href="#t_endereco" data-toggle="tab">ENDEREÇO</a>
                                </li>
                                <li>
                                    <a href="#t_financeiro" data-toggle="tab">FINANCEIRO</a>
                                </li>
                                <li>
                                    <a href="#t_boleto" data-toggle="tab">BOLETO</a>
                                </li>
                                <li>
                                    <a href="#t_spc" data-toggle="tab">IMPORTAÇÃO SPC</a>
                                </li>
                                <li>
                                    <a href="#t_caixa_conta" data-toggle="tab">CAIXA / CONTA</a>
                                </li>
                                <li>
                                    <a href="#t_venda" data-toggle="tab">VENDAS</a>
                                </li>
                                <li>
                                    <a href="#t_send_mail" data-toggle="tab">ENVIOS DE EMAILS</a>
                                </li>
                                <li>
                                    <a href="#t_pedidos" data-toggle="tab">PEDIDOS</a>
                                </li>
                                <li>
                                    <a href="#t_contratos" data-toggle="tab">CONTRATOS</a>
                                </li>
                            </ul>
                            <form action="" id="update">
                                <div class="tab-content padding tab-content-inline tab-content-bottom">
                                    <div class="tab-pane active" id="t_principal">
                                        <div class="row">
                                            <div class="form-group col-lg-3">
                                                <label>CNPJ</label>
                                                <input type="text" class="form-control empresa_cnpj" name="empresa_cnpj" onblur="BuscaCNPJ();"/>
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label>Inscrição Estadual</label>
                                                <input type="text" class="form-control empresa_ie" name="empresa_ie"/>
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label>Inscrição Estadual ST</label>
                                                <input type="text" class="form-control empresa_ie_st" name="empresa_ie_st"/>
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label>Inscrição Municipal</label>
                                                <input type="text" class="form-control empresa_im" name="empresa_im"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-3">
                                                <label>Nome Razão Social</label>
                                                <input type="text" class="form-control empresa_nome_razao" name="empresa_nome_razao"/>
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label>Nome Razão Fantasia</label>
                                                <input type="text" class="form-control empresa_nome_fantasia" name="empresa_nome_fantasia"/>
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label>CNAE</label>
                                                <input type="text" class="form-control empresa_cnae" name="empresa_cnae"/>
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label>Telefone</label>
                                                <input type="text" class="form-control empresa_telefone" name="empresa_telefone"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-3">
                                                <label>Celular</label>
                                                <input type="text" class="form-control empresa_celular" name="empresa_celular"/>
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label>Email</label>
                                                <input type="text" class="form-control empresa_email" name="empresa_email"/>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>URL Logo</label>
                                                <input type="text" class="form-control empresa_logo" name="empresa_logo"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="t_endereco">
                                        <div class="row">
                                            <div class="form-group col-lg-2">
                                                <label>CEP</label>
                                                <input type="text" class="form-control empresa_cep" name="empresa_cep" onblur="BuscaCEP(this.value);"/>
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label>Endereço</label>
                                                <input type="text" class="form-control empresa_endereco" name="empresa_endereco"/>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label>Número</label>
                                                <input type="text" class="form-control empresa_numero" name="empresa_numero"/>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label>Complemento</label>
                                                <input type="text" class="form-control empresa_complemento" name="empresa_complemento"/>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label>Bairro</label>
                                                <input type="text" class="form-control empresa_bairro" name="empresa_bairro"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-2">
                                                <label>UF</label>
                                                <input type="text" class="form-control empresa_estado" name="empresa_estado" readonly=""/>
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label>Cidade</label>
                                                <input type="text" class="form-control empresa_cidade" name="empresa_cidade" readonly=""/>
                                                <input type="hidden" class="empresa_cidade_ibge" name="empresa_cidade_ibge"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="t_financeiro">
                                        <div class="row">
                                            <div class="form-group col-lg-2">
                                                <label>Usar senha baixar?</label>
                                                <select name="empresa_download_config" class="form-control empresa_download_config">
                                                    <option value="0">SIM</option>
                                                    <option value="1">NÃO</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label>Senha baixar</label>
                                                <input type="password" class="form-control empresa_download_pass" name="empresa_download_pass"/>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label>Usar senha estornar?</label>
                                                <select name="empresa_reverse_config" class="form-control empresa_reverse_config">
                                                    <option value="0">SIM</option>
                                                    <option value="1">NÃO</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label>Senha estornar</label>
                                                <input type="password" class="form-control empresa_reverse_pass" name="empresa_reverse_pass"/>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label>Usar senha cancelar?</label>
                                                <select name="empresa_cancel_config" class="form-control empresa_cancel_config">
                                                    <option value="0">SIM</option>
                                                    <option value="1">NÃO</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label>Senha cancelar</label>
                                                <input type="password" class="form-control empresa_cancel_pass" name="empresa_cancel_pass"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="t_boleto">
                                        <button type="button" class="btn btn-primary" onclick="new_count();">Nova Conta</button>
                                        <hr />
                                        <div id="load_boleto"></div>
                                    </div>
                                    <div class="tab-pane" id="t_spc">
                                        <div class="row">
                                            <div class="form-group col-lg-2">
                                                <label>Status Contato</label>
                                                <select name="empresa_spc_status_contato" class="form-control empresa_spc_status_contato">
                                                    <option value="0">Ativo</option>
                                                    <option value="1">Inativo</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label>Tipo Contato</label>
                                                <select class="form-control empresa_spc_id_tipo_contato" name="empresa_spc_id_tipo_contato"></select>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label>Rota</label>
                                                <select class="form-control empresa_spc_id_rota" name="empresa_spc_id_rota"></select>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label>Região</label>
                                                <select class="form-control empresa_spc_id_regiao" name="empresa_spc_id_regiao"></select>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label>Forma de Pagamento</label>
                                                <select class="form-control empresa_spc_id_forma_pagamento" name="empresa_spc_id_forma_pagamento"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="t_caixa_conta">
                                        <div class="row">
                                            <div class="form-group col-lg-2">
                                                <label>Usar senha editar?</label>
                                                <select name="empresa_caixa_conta_config" class="form-control empresa_caixa_conta_config">
                                                    <option value="0">SIM</option>
                                                    <option value="1">NÃO</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label>Senha editar</label>
                                                <input type="password" class="form-control empresa_caixa_conta_pass" name="empresa_caixa_conta_pass"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="t_send_mail">
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label>Boleto para envio de Emails</label>
                                                <select name="empresa_boleto_id_mail" class="form-control empresa_boleto_id_mail"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="t_pedidos">
                                        <div class="row">
                                            <div class="form-group col-lg-2">
                                                <label>Usar senha deletar chip?</label>
                                                <select name="empresa_pedido_deletar_chip" class="form-control empresa_pedido_deletar_chip">
                                                    <option value="0">SIM</option>
                                                    <option value="1">NÃO</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label>Senha deletar chip</label>
                                                <input type="password" class="form-control empresa_pedido_deletar_chip_senha" name="empresa_pedido_deletar_chip_senha"/>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label>Usar senha editar chip?</label>
                                                <select name="empresa_pedido_update_chip" class="form-control empresa_pedido_update_chip">
                                                    <option value="0">SIM</option>
                                                    <option value="1">NÃO</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label>Senha editar chip</label>
                                                <input type="password" class="form-control empresa_pedido_update_chip_senha" name="empresa_pedido_update_chip_senha"/>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label>Fazer pedido sem contrato?</label>
                                                <select name="empresa_pedido_realizar_sem_contrato" class="form-control empresa_pedido_realizar_sem_contrato">
                                                    <option value="0">SIM</option>
                                                    <option value="1">NÃO</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label>Pedido s/ contrato assinado?</label>
                                                <select name="empresa_pedido_realizar_sem_contrato_assinado" class="form-control empresa_pedido_realizar_sem_contrato_assinado">
                                                    <option value="0">SIM</option>
                                                    <option value="1">NÃO</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-2">
                                                <label>Valor SMS</label>
                                                <input type="text" name="empresa_valor_sms" class="form-control empresa_valor_sms" id="valor_4"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="t_venda">
                                        <div class="row col-lg-12" align="center">
                                            <strong><i class="fa fa-list"></i> <i>Vendas a prazo:</i></strong>
                                            <hr />
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-3">
                                                <label>Tipo Documento</label>
                                                <select class="form-control empresa_venda_id_tipo_documento" name="empresa_venda_id_tipo_documento"></select>
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label>Plano de Contas</label>
                                                <select class="form-control empresa_venda_id_plano_conta" name="empresa_venda_id_plano_conta"></select>
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label>Fixa</label>
                                                <select class="form-control empresa_venda_fixo" name="empresa_venda_fixo">
                                                    <option value="0">NÃO</option>
                                                    <option value="1">SIM</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label>Aplicação Financeira</label>
                                                <select class="form-control empresa_venda_app_financeiro" name="empresa_venda_app_financeiro">
                                                    <option value="0">NÃO</option>
                                                    <option value="1">SIM</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="t_contratos">
                                        <div class="row">
                                            <div class="form-group col-lg-2">
                                                <label>Pedir senha ao isentar?</label>
                                                <select name="empresa_contrato_rastreamento_pedir_senha" class="form-control empresa_contrato_rastreamento_pedir_senha">
                                                    <option value="0">SIM</option>
                                                    <option value="1">NÃO</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label>Senha ao isentar</label>
                                                <input type="password" class="form-control empresa_contrato_rastreamento_senha" name="empresa_contrato_rastreamento_senha"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-12" align="right">
                                        <button type="button" class="btn btn-primary" onclick="update();">Gravar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="_modal_count" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Conta</h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <form action="" id="create_boleto">
                    <fieldset>
                        <div class="row">
                            <div class="form-group col-lg-9">
                                <label>Descrição</label>
                                <input type="text" class="form-control boleto_descricao" name="boleto_descricao"/>
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Banco</label>
                                <select class="form-control boleto_banco" name="boleto_banco">
                                    <option value="0">CAIXA</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-3">
                                <label>Modelo</label>
                                <select class="form-control boleto_modelo" name="boleto_modelo">
                                    <option value="0">SICOB</option>
                                    <option value="1">SINCO</option>
                                    <option value="2">SIGCB</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Agência</label>
                                <input type="text" class="form-control boleto_agencia" name="boleto_agencia"/>
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Conta</label>
                                <input type="text" class="form-control boleto_conta" name="boleto_conta"/>
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Conta DV</label>
                                <input type="text" class="form-control boleto_conta_digito" name="boleto_conta_digito"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-3">
                                <label>Status</label>
                                <select class="form-control boleto_status" name="boleto_status">
                                    <option value="0">ATIVO</option>
                                    <option value="1">INATIVO</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Conta Cedente</label>
                                <input type="text" class="form-control boleto_conta_cedente" name="boleto_conta_cedente"/>
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Conta Cedente DV</label>
                                <input type="text" class="form-control boleto_conta_cedente_digito" name="boleto_conta_cedente_digito"/>
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Carteira</label>
                                <input type="text" class="form-control boleto_carteira" name="boleto_carteira"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>Demonstrativo 1</label>
                                <input type="text" class="form-control boleto_demonstrativo_1" name="boleto_demonstrativo_1"/>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Demonstrativo 2</label>
                                <input type="text" class="form-control boleto_demonstrativo_2" name="boleto_demonstrativo_2"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>Demonstrativo 3</label>
                                <input type="text" class="form-control boleto_demonstrativo_3" name="boleto_demonstrativo_3"/>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Demonstrativo 4</label>
                                <input type="text" class="form-control boleto_demonstrativo_4" name="boleto_demonstrativo_4"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>Demonstrativo 5</label>
                                <input type="text" class="form-control boleto_demonstrativo_5" name="boleto_demonstrativo_5"/>
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Multa</label>
                                <input type="text" class="form-control boleto_multa" name="boleto_multa"/>
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Juros</label>
                                <input type="text" class="form-control boleto_juros" name="boleto_juros"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12" align="right">
                                <button type="button" class="btn btn-primary" onclick="create_boleto();">Gravar</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <!-- /.modal-body -->
            <div class="modal-footer">
            </div>
            <!-- /.modal-footer -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /#modal-1.modal fade -->

<div id="_modal_count_update" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Conta</h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <form action="" id="update_boleto">
                    <fieldset>
                        <div class="row">
                            <div class="form-group col-lg-9">
                                <label>Descrição</label>
                                <input type="text" class="form-control boleto_descricao" name="boleto_descricao"/>
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Banco</label>
                                <select class="form-control boleto_banco" name="boleto_banco">
                                    <option value="0">CAIXA</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-3">
                                <label>Modelo</label>
                                <select class="form-control boleto_modelo" name="boleto_modelo">
                                    <option value="0">SICOB</option>
                                    <option value="1">SINCO</option>
                                    <option value="2">SIGCB</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Agência</label>
                                <input type="text" class="form-control boleto_agencia" name="boleto_agencia"/>
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Conta</label>
                                <input type="text" class="form-control boleto_conta" name="boleto_conta"/>
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Conta DV</label>
                                <input type="text" class="form-control boleto_conta_digito" name="boleto_conta_digito"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-3">
                                <label>Status</label>
                                <select class="form-control boleto_status" name="boleto_status">
                                    <option value="0">ATIVO</option>
                                    <option value="1">INATIVO</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Conta Cedente</label>
                                <input type="text" class="form-control boleto_conta_cedente" name="boleto_conta_cedente"/>
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Conta Cedente DV</label>
                                <input type="text" class="form-control boleto_conta_cedente_digito" name="boleto_conta_cedente_digito"/>
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Carteira</label>
                                <input type="text" class="form-control boleto_carteira" name="boleto_carteira"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>Demonstrativo 1</label>
                                <input type="text" class="form-control boleto_demonstrativo_1" name="boleto_demonstrativo_1"/>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Demonstrativo 2</label>
                                <input type="text" class="form-control boleto_demonstrativo_2" name="boleto_demonstrativo_2"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>Demonstrativo 3</label>
                                <input type="text" class="form-control boleto_demonstrativo_3" name="boleto_demonstrativo_3"/>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Demonstrativo 4</label>
                                <input type="text" class="form-control boleto_demonstrativo_4" name="boleto_demonstrativo_4"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>Demonstrativo 5</label>
                                <input type="text" class="form-control boleto_demonstrativo_5" name="boleto_demonstrativo_5"/>
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Multa</label>
                                <input type="text" class="form-control boleto_multa" name="boleto_multa"/>
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Juros</label>
                                <input type="text" class="form-control boleto_juros" name="boleto_juros"/>
                                <input type="hidden" class="id_boleto"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12" align="right">
                                <button type="button" class="btn btn-primary" onclick="update_boleto();">Gravar</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <!-- /.modal-body -->
            <div class="modal-footer">
            </div>
            <!-- /.modal-footer -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /#modal-1.modal fade -->
<script>
    $(function(){
        var acao = "acao=load_regiao_empresa";
        $.ajax({
            type: 'GET',
            url: "_controller/_regiao.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].regiao_id + '">' + data_return.data[i].regiao_descricao + '</option>';
                }
                $('.empresa_spc_id_regiao').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function(){
        var acao = "acao=load_rota_empresa";
        $.ajax({
            type: 'GET',
            url: "_controller/_rota.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].rota_id + '">' + data_return.data[i].rota_descricao + '</option>';
                }
                $('.empresa_spc_id_rota').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function(){
        var acao = "acao=load_tipo_contato_empresa";
        $.ajax({
            type: 'GET',
            url: "_controller/_tipo_contato.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].tipo_contato_id + '">' + data_return.data[i].tipo_contato_descricao + '</option>';
                }
                $('.empresa_spc_id_tipo_contato').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function(){
        var acao = "acao=load_forma_pagamento";
        $.ajax({
            type: 'GET',
            url: "_controller/_forma_pagamento.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].forma_pagamento_id + '">' + data_return.data[i].forma_pagamento_descricao + '</option>';
                }
                $('.empresa_spc_id_forma_pagamento').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function(){
        var acao = "acao=load_boletos_empresa";
        $.ajax({
            type: 'GET',
            url: "_controller/_empresa.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].boleto_id + '">' + data_return.data[i].boleto_descricao + '</option>';
                }
                $('.empresa_boleto_id_mail').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function(){
        var acao = "acao=load_plano_conta";
        $.ajax({
            type: 'GET',
            url: "_controller/_plano_conta.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].plano_conta_id + '">' + data_return.data[i].plano_conta_classificacao + ' ' + data_return.data[i].plano_conta_descricao + '</option>';
                }
                $('.empresa_venda_id_plano_conta').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function(){
        var acao = "acao=load_tipo_documento";
        $.ajax({
            type: 'GET',
            url: "_controller/_tipo_documento.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].tipo_documento_id + '">' + data_return.data[i].tipo_documento_descricao + '</option>';
                }
                $('.empresa_venda_id_tipo_documento').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    function update(){
        var dados = $("#update").serialize();
        var acao = "&acao=update";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_empresa.php",
            data: dados+acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                if(data_return.type === 'error'){
                    $("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                }else{
                    $("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                }
            }
        });
    }
    function create_boleto(){
        var dados = $("#create_boleto").serialize();
        var acao = "&acao=create_boleto";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_empresa.php",
            data: dados+acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                if(data_return.type === 'error'){
                    $("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                }else{
                    $("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                }
            }
        });
    }
    function update_boleto(){
        var dados = $("#update_boleto").serialize();
        var acao = "&acao=update_boleto&id="+$(".id_boleto").val();
        
        $.ajax({
            type: 'POST',
            url: "_controller/_empresa.php",
            data: dados+acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                if(data_return.type === 'error'){
                    $("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                }else{
                    $("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                }
            }
        });
    }
    function load_update_boleto(id_boleto){
        var acao = "&acao=load_update_boleto&id="+id_boleto;
        
        $.ajax({
            type: 'POST',
            url: "_controller/_empresa.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $("#_modal_count_update").modal('show');
                $(".boleto_descricao").val(data_return[0].boleto_descricao);
                $(".boleto_banco").val(data_return[0].boleto_banco);
                $(".boleto_modelo").val(data_return[0].boleto_modelo);
                $(".boleto_agencia").val(data_return[0].boleto_agencia);
                $(".boleto_conta").val(data_return[0].boleto_conta);
                $(".boleto_conta_digito").val(data_return[0].boleto_conta_digito);
                $(".boleto_conta_cedente").val(data_return[0].boleto_conta_cedente);
                $(".boleto_conta_cedente_digito").val(data_return[0].boleto_conta_cedente_digito);
                $(".boleto_carteira").val(data_return[0].boleto_carteira);
                $(".boleto_demonstrativo_1").val(data_return[0].boleto_demonstrativo_1);
                $(".boleto_demonstrativo_2").val(data_return[0].boleto_demonstrativo_2);
                $(".boleto_demonstrativo_3").val(data_return[0].boleto_demonstrativo_3);
                $(".boleto_demonstrativo_4").val(data_return[0].boleto_demonstrativo_4);
                $(".boleto_demonstrativo_5").val(data_return[0].boleto_demonstrativo_5);
                $(".boleto_juros").val(data_return[0].boleto_juros);
                $(".boleto_multa").val(data_return[0].boleto_multa);
                $(".boleto_status").val(data_return[0].boleto_status);
                $(".id_boleto").val(id_boleto);
            }
        });
    }
    function new_count(){
        $("#_modal_count").modal('show');
    }
    function close_empresa(){
        $("#_modal_count").modal('hide');
        $("#_modal").modal('hide');
        $("#_modal_count_update").modal('hide');
        //carrega_pagina('empresa', 'update.php');
    }
    $(function(){
        var acao = "acao=load_update";
        $.ajax({
            type: 'POST',
            url: "_controller/_empresa.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $(".empresa_cnpj").val(data_return[0].empresa_cnpj);
                $(".empresa_ie").val(data_return[0].empresa_ie);
                $(".empresa_ie_st").val(data_return[0].empresa_ie_st);
                $(".empresa_im").val(data_return[0].empresa_im);
                $(".empresa_nome_razao").val(data_return[0].empresa_nome_razao);
                $(".empresa_nome_fantasia").val(data_return[0].empresa_nome_fantasia);
                $(".empresa_cnae").val(data_return[0].empresa_cnae);
                $(".empresa_cep").val(data_return[0].empresa_cep);
                $(".empresa_endereco").val(data_return[0].empresa_endereco);
                $(".empresa_numero").val(data_return[0].empresa_numero);
                $(".empresa_complemento").val(data_return[0].empresa_complemento);
                $(".empresa_bairro").val(data_return[0].empresa_bairro);
                $(".empresa_estado").val(data_return[0].empresa_estado);
                $(".empresa_cidade").val(data_return[0].empresa_cidade);
                $(".empresa_cidade_ibge").val(data_return[0].empresa_cidade_ibge);
                $(".empresa_telefone").val(data_return[0].empresa_telefone);
                $(".empresa_celular").val(data_return[0].empresa_celular);
                $(".empresa_email").val(data_return[0].empresa_email);
                $(".empresa_logo").val(data_return[0].empresa_logo);
                $(".empresa_senha").val(data_return[0].empresa_senha);
                $(".empresa_download_config").val(data_return[0].empresa_download_config);
                $(".empresa_download_pass").val(data_return[0].empresa_download_pass);
                $(".empresa_reverse_config").val(data_return[0].empresa_reverse_config);
                $(".empresa_reverse_pass").val(data_return[0].empresa_reverse_pass);
                $(".empresa_cancel_config").val(data_return[0].empresa_cancel_config);
                $(".empresa_cancel_pass").val(data_return[0].empresa_cancel_pass);
                $(".empresa_spc_id_tipo_contato").val(data_return[0].empresa_spc_id_tipo_contato);
                $(".empresa_spc_id_regiao").val(data_return[0].empresa_spc_id_regiao);
                $(".empresa_spc_id_rota").val(data_return[0].empresa_spc_id_rota);
                $(".empresa_spc_id_forma_pagamento").val(data_return[0].empresa_spc_id_forma_pagamento);
                $(".empresa_spc_status_contato").val(data_return[0].empresa_spc_status_contato);
                $(".empresa_caixa_conta_config").val(data_return[0].empresa_caixa_conta_config);
                $(".empresa_caixa_conta_pass").val(data_return[0].empresa_caixa_conta_pass);
                $(".empresa_boleto_id_mail").val(data_return[0].empresa_boleto_id_mail);
                $(".empresa_venda_id_tipo_documento").val(data_return[0].empresa_venda_id_tipo_documento);
                $(".empresa_venda_id_plano_conta").val(data_return[0].empresa_venda_id_plano_conta);
                $(".empresa_venda_fixo").val(data_return[0].empresa_venda_fixo);
                $(".empresa_venda_app_financeiro").val(data_return[0].empresa_venda_app_financeiro);
                $(".empresa_pedido_deletar_chip").val(data_return[0].empresa_pedido_deletar_chip);
                $(".empresa_pedido_deletar_chip_senha").val(data_return[0].empresa_pedido_deletar_chip_senha);
                $(".empresa_contrato_rastreamento_pedir_senha").val(data_return[0].empresa_contrato_rastreamento_pedir_senha);
                $(".empresa_contrato_rastreamento_senha").val(data_return[0].empresa_contrato_rastreamento_senha);
                $(".empresa_pedido_realizar_sem_contrato").val(data_return[0].empresa_pedido_realizar_sem_contrato);
                $(".empresa_pedido_realizar_sem_contrato_assinado").val(data_return[0].empresa_pedido_realizar_sem_contrato_assinado);
                $(".empresa_pedido_update_chip_senha").val(data_return[0].empresa_pedido_update_chip_senha);
                $(".empresa_valor_sms").val(data_return[0].empresa_valor_sms);
            }
        });
    });
    $(function(){
        var acao = "acao=load_boleto";
        $.ajax({
            type: 'POST',
            url: "_controller/_empresa.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#load_boleto").html(data);
            }
        });
    });
    function BuscaCNPJ() {
        var CNPJ = $(".empresa_cnpj").val();
        $.ajax({
            type: "GET",
            beforeSend: load_in(),
            url: "https://www.receitaws.com.br/v1/cnpj/" + CNPJ,
            dataType: "jsonp",
            success: function (data) {
                if (data != null) {
                    $(".empresa_nome_razao").val(data.nome);
                    $(".empresa_nome_fantasia").val(data.fantasia);
                    $(".empresa_numero").val(data.numero);
                    
                    var CEP = data.cep
                    var CEP_UNO = CEP.replace(".", "");
                    var CEP_DUO = CEP_UNO.replace("-", "");
                    $(".empresa_cep").val(CEP_DUO);
                    $(".empresa_bairro").val(data.bairro);
                    $(".empresa_endereco").val(data.logradouro);
                    $(".empresa_complemento").val(data.complemento);
                    $(".empresa_email").val(data.email);
                    $(".empresa_telefone").val(data.telefone);
                    BuscaCEP(CEP_DUO);
                }
                load_out();
            }
        });
        load_out();
    }
    function BuscaCEP(CEP_INFO){
        $.ajax({
            type: "GET",
            beforeSend: load_in(),
            url: "http://viacep.com.br/ws/"+CEP_INFO+"/json/?callback=",
            dataType: "jsonp",
            success: function (data) {
                if (data != null) {
                    $(".empresa_estado").val(data.uf);
                    $(".empresa_cidade").val(data.localidade);
                    $(".empresa_cidade_ibge").val(data.ibge);
                    if($(".empresa_bairro").val() === ''){
                        $(".empresa_bairro").val(data.bairro);
                    }
                    if($(".empresa_complemento").val() === ''){
                        $(".empresa_complemento").val(data.complemento);
                    }
                    if($(".empresa_endereco").val() === ''){
                        $(".empresa_endereco").val(data.logradouro);
                    }
                }
                load_out();
            }
        });
        load_out();
    }
    $("#valor_1").maskMoney({thousands:'', decimal:'.'});
    $("#valor_2").maskMoney({thousands:'', decimal:'.'});
    $("#valor_3").maskMoney({thousands:'', decimal:'.'});
    $("#valor_4").maskMoney({thousands:'', decimal:'.'});
    $('#peso_1').priceFormat({prefix: '', centsSeparator: '.', thousandsSeparator: '',centsLimit: 3});
    $('#peso_2').priceFormat({prefix: '', centsSeparator: '.', thousandsSeparator: '',centsLimit: 3});
</script>