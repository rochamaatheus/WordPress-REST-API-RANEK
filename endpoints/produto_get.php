<?php

function api_produto_get($request) {
  $slug = $request['slug'];
  $post_id = get_produto_id_by_slug($slug);

  if($post_id) {
    $post_meta = get_post_meta($post_id);

    $images = get_attached_media('image', $post_id);
    $images_array = null;

    if($images) {
      $images_array = array();
      foreach($images as $key => $value) {
        $images_array[] = array(
          'titulo' => $value->post_name,
          'src' => $value->guid
        );
      }
    }

    $response = array(
      'id' => $slug,
      'fotos' => $images_array,
      'nome' => $post_meta['nome'][0],
      'preco' => $post_meta['preco'][0],
      'descricao' => $post_meta['descricao'][0],
      'vendido' => $post_meta['vendido'][0],
      'usuario_id' => $post_meta['usuario_id'][0],
    );

  } else {
    $response =new WP_Error('naoexiste', 'Produto não encontrado.', array('status' => 404));
  }

  return rest_ensure_response($response);
}

function registrar_api_produto_get() {
  register_rest_route('api', '/produto/(?P<slug>[-\w]+)', array(
    array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => 'api_produto_get',
    ),
  ));
}

add_action('rest_api_init', 'registrar_api_produto_get');


?>