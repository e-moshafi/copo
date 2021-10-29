<?php
class modal
{
  function user_result($id = "")
  {
    global $wpdb;
    if (empty($id)) {
      $user_result = $wpdb->get_results("SELECT * FROM wp_users");
    } else {
      $user_result = $wpdb->get_results("SELECT * FROM wp_users where ID=" . $id . "");
    }
    return $user_result;
  }
}
class admin
{
  function create_porgram()
  {
    $modal = new modal();
?>
    <div class="continer justify-content-center">
      <div class="row">
        <div class="col-12">
          <script>
            $(document).ready(function() {
              calendars('calendar-program', '<?php echo  plugins_url('/', __FILE__); ?>');
            });
          </script>
          <div id="calendar-program">
          </div>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-9">
          <div class="program-form program-form-submit shadow-lg deactivate  justify-content-center">
            <div class="row">
              <div class="col-6">
                <i class="fa fa-times" style="font-size:15px;cursor:pointer" onclick="modal_show('.program-form-submit')"></i>
              </div>
            </div>
            <div class="row justify-content-center">
              <div class="col-lg justify-content-center">
                <form class="form-add-event">
                  <div class="row">
                    <div class="col-sm">
                      <div class="form-group">
                        <label for="input">title</label>
                        <input type="text" class="form-control" id="input" name="title" require/>
                      </div>
                    </div>
                    <div class="col-sm">
                      <div class="form-group">
                        <label for="input">color</label>
                        <input type="color" class="form-control" id="input" name="color"/>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm">
                      <div class="form-group">
                        <label for="start-date">start</label>
                        <input type="date" class="form-control" id="start-date"  name="start_date" require/>
                      </div>
                    </div>
                    <div class="col-sm">
                      <div class="form-group">
                        <label for="end-date">end</label>
                        <input type="date" class="form-control" id="end-date" name="end_date" require/>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm">
                      <div class="form-group">
                        <label for="textarea-description">decription</label>
                       <textarea  class="form-control" id="textarea-description"  name="description" rows="5"></textarea>
                        <?php //wp_editor( '', 'textarea-description' ); ?>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-3">
                      <div class="form-group">
                        <label for="cover">cover</label>
                        <input type="file" onchange="preview_show('cover-preview',true)" class="form-control" id="cover"  name="cover"/>
                      </div>
                    </div>
                    <div class="col-sm">
                       <img id="cover-preview" style="width:200px;height:200px;display:none"/>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm">
                      <div class="form-group">
                        <input type="hidden" name="add_event"/>
                      <button type="button"  class="btn btn-success w-100" id="add-event-btn" onclick="form_send('.form-add-event','<?php echo plugins_url('/',__FILE__) ?>','add-event-btn')">add event</button>
                      </div> 
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-9">
          <div class="program-form program-form-update shadow-lg deactivate">
            <div class="row">
              <div class="col-6">
                <i class="fa fa-times" style="font-size:15px;cursor:pointer" onclick="modal_show('.program-form-update')"></i>
              </div>
            </div>
            <div class="row">
              <div class="col-lg">
                <div class="program-form-update-show">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


<?php
  }
}
function addtomenu()
{
  $admin = new admin();
  add_menu_page('copo', 'copo', 'manage_options', 'copo', array($admin, "create_porgram"));
}
function create_table(){
  dbDelta("CREATE TABLE  copo_plugin  (
    id  int NOT NULL AUTO_INCREMENT,
    title  varchar(355) DEFAULT NULL,
    description  text,
    cover  varchar(500) DEFAULT NULL,
    files  json DEFAULT NULL,
    color  varchar(255) NOT NULL,
    start_date  varchar(355) NOT NULL,
    end_date  varchar(355) NOT NULL,
    date  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   PRIMARY KEY (id)
 ) ");
}
function asset()
{
  //scripts
  wp_enqueue_script(
    'jquerymin',
    plugins_url('/asset/js/jquery.js', __FILE__)
  );
  wp_enqueue_script(
    'bootstrapjs',
    plugins_url('/asset/js/bootstrap/bootstrap.min.js', __FILE__)
  );
  wp_enqueue_script(
    'fullcalendarjs',
    plugins_url('/asset/js/fullcalendar/main.js', __FILE__)
  );
  wp_enqueue_script(
    'jsmin',
    plugins_url('/asset/js/js.min.js', __FILE__)
  );
  //style
  wp_enqueue_style(
    'bootstrapcss',
    plugins_url('/asset/css/bootstrp/bootstrap.min.css', __FILE__)
  );
  wp_enqueue_style(
    'stylemin',
    plugins_url('/asset/css/style.min.css', __FILE__)
  );

  wp_enqueue_style(
    'fullcalendarcss',
    plugins_url('/asset/css/fullcalendar/main.css', __FILE__)
  );
  wp_enqueue_style(
    'fontawesome',
    plugins_url('/asset/css/fontawesome/css/font-awesome.css', __FILE__)
  );
}
add_action('init', "asset");
add_action("admin_menu", "addtomenu");
add_action('admin_enqueue_scripts', 'asset');
add_action('wp_enqueue_scripts', 'asset');
register_activation_hook( __FILE__, 'create_table' );