<?php
/*
Template Name: Profile
*/
?>

<?php get_header(); ?>

<div class="clear"></div>
<div class="prepend-top"></div>

<div class="span-14 prepend-1 right-colborder">
  <div class="item green">
    <div class="title pull-1">
      <div class="shadow"></div>
      <h1>Editando perfil</h1>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
  </div>

  <div class="clear"></div>

  <form class="background clearfix">
    <h2>Informações de login</h2>
    <p class="clearfix prepend-1">
      <label for="username">Nome de usuário</label>
      <br/>
      <input type="text" id="username" name="username" value="" class="text span-12" />
    </p>
    <p class="clearfix prepend-1">
      <label for="password">Senha</label>
      <br/>
      <input type="text" id="password" name="password" value="" class="text span-12" />
    </p>
    <p class="clearfix prepend-1">
      <label for="password_confirmation">Confirmação da senha</label>
      <br/>
      <input type="text" id="password_confirmation" name="password_confirmation" value="" class="text span-12" />
    </p>

    <h2>Informações de contato</h2>
    <p class="clearfix prepend-1">
      <label for="responsable">Responsável</label>
      <br/>
      <input type="text" id="responsable" name="responsable" value="" class="text span-12" />
    </p>
    <p class="clearfix prepend-1">
      <label for="email">E-mail</label>
      <br/>
      <input type="text" id="_email" name="email" value="" class="text span-12" />
    </p>
    <p class="clearfix prepend-1">
      <label for="phone">Telefone</label>
      <br/>
      <input type="text" id="ddd" name="ddd" value="" class="text span-1" /> <input type="text" id="telefone" name="telefone" value="" class="text span-5"/>
    </p>
    <p class="clearfix prepend-1">
      <label for="estado">Estado:</label>
      <br />
      <select name="estado">                            
        <option value="ac">Acre</option>
        <option value="al">Alagoas</option>
        <option value="ap">Amapá</option>
        <option value="am">Amazonas</option>
        <option value="ba">Bahia</option>
        <option value="ce">Ceará</option>
        <option value="df">Distrito Federal</option>
        <option value="es">Espirito Santo</option>
        <option value="go">Goiás</option>
        <option value="ma">Maranhão</option>
        <option value="ms">Mato Grosso do Sul</option>
        <option value="mt">Mato Grosso</option>
        <option value="mg">Minas Gerais</option>
        <option value="pa">Pará</option>
        <option value="pb">Paraíba</option>
        <option value="pr">Paraná</option>
        <option value="pe">Pernambuco</option>
        <option value="pi">Piauí</option>
        <option value="rj">Rio de Janeiro</option>
        <option value="rn">Rio Grande do Norte</option>
        <option value="rs">Rio Grande do Sul</option>
        <option value="ro">Rondônia</option>
        <option value="rr">Roraima</option>
        <option value="sc">Santa Catarina</option>
        <option value="sp">São Paulo</option>
        <option value="se">Sergipe</option>
        <option value="to">Tocantins</option>
      </select>
    </p>

    
    <h2>Informações da banda</h2>
    <p class="prepend-1 clearfix">
      <label for="bands_name">Nome da banda</label>
      <br/>
      <input type="text" id="bands_name" name="bands_name" value="" class="text span-12" />
    </p>
    <p class="clearfix prepend-1">
      <label for="site">Site</label>
      <br/>
      <input type="text" id="site" name="site" value="" class="text span-12" />
    </p>
    <p class="clearfix prepend-1">
      <label for="youtube">URL do YouTube</label>
      <br/>
      <input type="text" id="youtube" name="youtube" value="" class="text span-12" />
    </p>
    <p class="clearfix prepend-1">
      <label for="music">Música #1</label>
      <br/>
      <input type="file" id="music" name="music" value="" class="text span-12" />
    </p>
    <p class="clearfix prepend-1">
      <label for="music">Música #2</label>
      <br/>
      <input type="file" id="music" name="music" value="" class="text span-12" />
    </p>
    <p class="clearfix prepend-1">
      <label for="music">Música #3</label>
      <br/>
      <input type="file" id="music" name="music" value="" class="text span-12" />
    </p>
    <p class="clearfix prepend-1">
      <label for="photo">Foto #1</label>
      <br/>
      <input type="file" id="photo" name="photo" value="" class="text span-12" />
    </p>
    <p class="clearfix prepend-1">
      <label for="photo">Foto #2</label>
      <br/>
      <input type="file" id="photo" name="photo" value="" class="text span-12" />
    </p>
    <p class="clearfix prepend-1">
      <label for="photo">Foto #3</label>
      <br/>
      <input type="file" id="photo" name="photo" value="" class="text span-12" />
    </p>
  </form>

</div>

<?php get_footer(); ?>
