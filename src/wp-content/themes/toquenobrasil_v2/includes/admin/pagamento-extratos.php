<?php
if(isset($_POST['action']) && ($_POST['action'] == 'cancelar-transacao'  || $_POST['action'] == 'confirmar-transacao') && isset($_POST['TransacaoID'])){
    $TransacaoID = $_POST['TransacaoID'];
    $trans = $wpdb->get_row("SELECT * FROM pagseguro_transacoes WHERE TransacaoID = '$TransacaoID'", ARRAY_A);
    if($trans['StatusTransacao'] === 'Aguardando Pagto'){
        $novoStatus = $_POST['action'] == 'cancelar-transacao' ? 'Cancelado' : 'Aprovado';

        $props = "";
        $vals = "";
        foreach($trans as $key => $val){
            if($key != 'insert_timestamp'){
                if($key === 'StatusTransacao')
                    $val = $novoStatus;

                $props .= $props ? ", $key" : $key;
                $vals .= $vals ? ", '$val'" : "'$val'";

            }
        }
        $wpdb->query("INSERT INTO pagseguro_transacoes($props) VALUES ($vals)");
        
        if($novoStatus == 'Aprovado'){
            $wpdb->query("UPDATE $wpdb->postmeta SET meta_key='inscrito' WHERE meta_id = '".$trans['Referencia']."'");
            add_post_meta($trans['ProdID'], 'transacao_inscricao-'.$trans['Referencia'], $trans['TransacaoID']);
            do_action('tnb_artista_inscricao_confirmada_em_evento_pago',$trans['Referencia']);
        }
    }
}

$eventos = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE $wpdb->posts.ID IN (SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'evento_inscricao_cobrada') AND post_status = 'publish'");
$_GET['evento_id'] = $_GET['evento_id'] ? $_GET['evento_id'] : null;

$cols = array(
    'insert_timestamp'=>'Data Retorno',
    'TransacaoID' => 'TransacaoID',
    'StatusTransacao' => 'Status',
    'DataTransacao' => 'Data Transação',
    'TipoPagamento' => 'Tipo de Pagamento',
    'Referencia' => 'Referência',
    'ProdValor' => "Valor",
    'CliNome' => "Cliente",
    'CliEmail' => "E-Mail",
    'CliTelefone' => "Telefone"
);

?>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery("#extrato-evento-id").change(function(){
            
            if(jQuery(this).val() == '')
                jQuery("#extrato").html("");
            else 
                window.location.replace ("?page=<?php echo $_GET['page']; ?>&evento_id="+jQuery(this).val());
            
        });
        jQuery('.confirmar, .cancelar').click(function(){
            var transacaoid = jQuery(this).attr('transacaoid');
            var acao = jQuery(this).attr('acao');
            var submit;
            if(acao == 'confirmar')
                submit = confirm('Deseja definir o status da transação de ID ' + transacaoid + ' para Aprovado?\n\nEsta ação não poderá ser desfeita e confirmará a inscrição do artista que efetuou este pagamento.');
            else
                submit = confirm('Deseja definir o status da transação de ID ' + transacaoid + ' para Cancelado?\n\n Esta ação não poderá ser desfeita.');
            
            if(submit){
                jQuery('#form-action-transacao').val(transacaoid);
                jQuery('#form-action-action').val(acao+'-transacao');
                jQuery('#form-action').submit();
                return true;
            }else{
                return false;
            }
            
        });
    });
</script>

<style>
    .verificar { background: #fcefa1; }
    
    .legenda {margin-bottom: 11px;}
    .legenda .verificar {width: 20px; height: 20px; float:left; }
    
    #extrato table tbody 
</style>

<div class="wrap">
    <form method="post" id="form-action">
        <input type="hidden" id="form-action-action" name="action" value=""/>
        <input type="hidden" id="form-action-transacao" name="TransacaoID" value=""/>
    </form>
    
    <h2>Extrato de retornos do PagSeguro</h2>
    oportunidade: <select id="extrato-evento-id">
        <option value="">Selecione uma oportunidade</option>
    <?php foreach($eventos as $evento): if($evento->ID == $_GET['evento_id']) $selected_evento = $evento;?>
        <option value = "<?php echo $evento->ID; ?>" <?php if($evento->ID == $_GET['evento_id']) echo 'selected="selected"' ?>><?php echo htmlentities($evento->post_title); ?></option>
    <?php endforeach; ?>
    <select>
    <hr/>
    <div id="extrato">
    <?php 
    if(isset($selected_evento)): 
        $aprovadas = $wpdb->get_results("SELECT * FROM pagseguro_transacoes WHERE ProdID = '$selected_evento->ID' AND StatusTransacao = 'Aprovado'");
        $canceladas = $wpdb->get_results("SELECT * FROM pagseguro_transacoes WHERE ProdID = '$selected_evento->ID' AND StatusTransacao = 'Cancelado'");
        $aguardando = $wpdb->get_results("SELECT *, (insert_timestamp < CURRENT_DATE() - INTERVAL 1 WEEK) as verificar FROM pagseguro_transacoes WHERE ProdID = '$selected_evento->ID' AND StatusTransacao = 'Aguardando Pagto' AND TransacaoID NOT IN (SELECT DISTINCT (TransacaoID) FROM pagseguro_transacoes WHERE ProdID = '$selected_evento->ID' AND (StatusTransacao = 'Aprovado' OR StatusTransacao = 'Cancelado')) ");
        //_pr($canceladas);
        
        ?>
        <h3>retornos da oportunidade: <?php echo $selected_evento->post_title; ?></h3>
        <div class="legenda">
            <div class="verificar">&nbsp;</div> &nbsp; <small>Pagamentos que estão aguardando a mais de uma semana um retorno do PagSeguro. Verificar no PagSeguro pela TransaçãoID e atualizar status se necessário.</small>
        </div>
        <table class="widefat">
            <thead>
                <tr>
                <?php foreach($cols as $col): ?>
                    <th><?php echo $col; ?></th>
                <?php endforeach; ?>
                </tr>
            </thead>
            
            <?php if($aguardando): ?>
                <tbody class='aguardando'>
                    <tr><th colspan="<?php echo count($cols); ?>"><br/>Aguardando</th></tr>
                <?php foreach($aguardando as $trans): ?>
                    <tr <?php if($trans->verificar) echo 'class="verificar"'?>>
                    <?php foreach($cols as $col => $label): ?>
                        <?php if($col === 'TransacaoID'): ?>
                            <td>
                                <?php echo $trans->$col; ?><br/>
                                <a href="#" class='confirmar' transacaoid="<?php echo $trans->TransacaoID; ?>" acao="confirmar">confirmar</a> - 
                                <a href="#" class='cancelar' transacaoid="<?php echo $trans->TransacaoID; ?>" acao="cancelar">cancelar</a>

                            </td>
                        <?php else: ?>
                            <td><?php echo $trans->$col; ?></td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            <?php endif; ?>
                
            <?php if($aprovadas): ?>
                <tbody class='aprovadas'>
                    <tr><th colspan="<?php echo count($cols); ?>"><br/>Aprovadas</th></tr>
                <?php foreach($aprovadas as $trans): ?>
                    <tr>
                    <?php foreach($cols as $col => $label): ?>
                        <td><?php echo $trans->$col; ?></td>
                    <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            <?php endif; ?>
                
            <?php if($canceladas): ?>
                <tbody class='canceladas'>
                    <tr><th colspan="<?php echo count($cols); ?>"><br/>Canceladas</th></tr>
                <?php foreach($canceladas as $trans): ?>
                    <tr>
                    <?php foreach($cols as $col => $label): ?>
                        <td><?php echo $trans->$col; ?></td>
                    <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            <?php endif; ?>
        </table>
    <?php endif; ?>
    </div>
</div>

