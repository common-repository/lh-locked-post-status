<?php



class LH_Custom_post_status_class {

var $newstatusname;
var $newstatuslabel;
var $newstatuslabel_count;
var $viewcapability;
var $editcapability;

function current_user_can_view() {
	/**
	 * Default capability to grant ability to view status content (if the status is set to non public)
	 *
	 * @since 0.3.0
	 *
	 * @return string
	 */

if ($this->viewcapability == "read"){

return true;


} else {

	return current_user_can($this->viewcapability);

}
}


public function append_post_status_list(){
     global $post;
     $complete = '';
     $label = '';

if ( current_user_can($this->editcapability) ) {

if($post->post_status == $this->newstatusname){
          echo '
          <script>
          jQuery(document).ready(function($){
$("#post-status-display" ).text("'.ucwords($this->newstatuslabel).'");
$("select#post_status").append("<option value=\"'.$this->newstatusname.'\" selected=\"selected\">'.ucwords($this->newstatuslabel).'</option>");
$(".misc-pub-post-status label").append("<span id=\"post-status-display\"> '.ucwords($this->newstatuslabel).'</span>");
          });
          </script>
          ';
          } else {


          echo '
          <script>
          jQuery(document).ready(function($){
$("select#post_status").append("<option value=\"'.$this->newstatusname.'\" >'.ucwords($this->newstatuslabel).'</option>");
          });
          </script>
          ';

}

}
     
} 


function display_locked_state( $states ) {
     global $post;
     $arg = get_query_var( 'post_status' );
     if($arg != $this->newstatusname){
          if($post->post_status == $this->newstatusname){
               return array($this->newstatuslabel);
          }
     }
    return $states;
}



function create_locked_custom_post_status(){

$args = array(
	  'public' => $this->current_user_can_view(),
          'label'                     => _x( $this->newstatuslabel, 'post' ),
          'show_in_admin_all_list'    => false,
          'show_in_admin_status_list' => true,
          'label_count'               => _n_noop( $this->newstatuslabel_count, $this->newstatuslabel_count) );


if (isset($this->public)){
$args['public'] = $this->public;
}


if (isset($this->private)){
$args['private'] = $this->private; 

}



register_post_status( $this->newstatusname, $args);
}




function protect_locked_posts( $caps, $cap, $user_id, $args ) {



/* If the user doesn't have manage_options, remove their ability to edit or delete the post type object. */

if ( 'edit_post' == $cap || 'delete_post' == $cap ) {

if (!user_can( $user_id, "manage_options")){

$post = get_post( $args[0] );
		
if ($post->post_status == $this->newstatusname){

$caps[] = 'do_not_allow';

}

}

} else {

  if (isset($args[0]) and $post = get_post( $args[0] ) and ($post->post_status == $this->newstatusname)) {
	



$caps[] = 'read_private_posts';


	
  }

}
	
/* Return the capabilities required by the user. */
return $caps;
}


public function append_post_status_bulk_edit() {

echo '

<script>

jQuery(document).ready(function($){

$(".inline-edit-status select ").append("<option value=\"'.$this->newstatusname.'\">'.ucwords($this->newstatuslabel).'</option>");

});

</script>

';

}




public function __construct($name,$label,$count,$viewcapability = 'read', $editcapability = 'manage_options') {

$this->newstatusname = $name;
$this->newstatuslabel = $label;
$this->newstatuslabel_count = $count;
$this->viewcapability = $viewcapability;
$this->editcapability = $editcapability;


add_action( 'init', array($this,"create_locked_custom_post_status"));

add_filter( 'display_post_states', array($this,"display_locked_state"));

add_action('admin_footer-post.php', array($this,"append_post_status_list"));

add_filter( 'map_meta_cap', array($this,"protect_locked_posts"),9,4);

//JavaScript for bulk edit support
add_action( 'admin_footer-edit.php', array( $this, 'append_post_status_bulk_edit' ));




	}





}

?>