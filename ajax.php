<?php
//connect to database
require_once "../../../wp-config.php";
try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET . "", DB_USER, DB_PASSWORD);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
function data_now()
{
    $now = DateTime::createFromFormat('U.u', microtime(true));

    return  $now->format("m_d_Y_H_i_s_u");
}
//add new event
if (isset($_POST['add_event'])) {
    if (!empty($_FILES['cover']['name'])) {
        $cover_name = data_now() . $_FILES['cover']['name'];
        $folder = 'files/' . $cover_name;
        if (move_uploaded_file($_FILES['cover']['tmp_name'], $folder)) {
            echo 'upload successfully';
        } else {
            echo 'upload Break';
        }
    } else {
        $cover_name = "";
    }
    try {
        $stmt = $conn->prepare("INSERT INTO copo_plugin (title,description,cover,color,start_date,end_date) VALUES (?,?, ?, ?, ?, ?)");
        $stmt->execute(array($_POST['title'], $_POST['description'], $cover_name, $_POST['color'], $_POST['start_date'], $_POST['end_date']));
        echo "send successfully";
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    //update event
} elseif (isset($_POST['update_event_send'])) {
    if (!empty($_FILES['cover']['name'])) {
        $cover_name = data_now() . $_FILES['cover']['name'];
        $folder = 'files/' . $cover_name;
        if (move_uploaded_file($_FILES['cover']['tmp_name'], $folder)) {
            echo 'upload successfully';
            if (isset($_POST['old_cover'])) {
                unlink("files/" . $_POST['old_cover']);
            }
        } else {
            echo 'upload Break';
        }
    } else {
        if (isset($_POST['old_cover'])) {
            $cover_name = $_POST['old_cover'];
        } else {
            $cover_name = "";
        }
    }

    try {
        $stmt = $conn->prepare("INSERT INTO copo_plugin (title,description,cover,color,start_date,end_date) VALUES (?,?, ?, ?, ?, ?)");
        $stmt = $conn->prepare("UPDATE copo_plugin SET title=?,description=?,cover=?,color=?,start_date=?,end_date=? where id=?");
        $stmt->execute(array($_POST['title'], $_POST['description'], $cover_name, $_POST['color'], $_POST['start_date'], $_POST['end_date'], $_POST['update_event_send']));
        echo "send successfully";
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} elseif (isset($_POST['delete_event'])) {
$stmt=$conn->prepare("SELECT * FROM copo_plugin WHERE id=?");
$stmt->execute(array($_POST['delete_event']));
$row=$stmt->fetch();
if ($stmt->rowCount()>0) {
    if (!empty($row['cover'])) {
       if( unlink("files/" . $row['cover'])){
           echo "delete cover";
       }
    }
    try{
        $stmt=$conn->prepare("DELETE FROM copo_plugin WHERE id=?");
        $stmt->execute(array($_POST['delete_event']));
        echo "delete successfully";
       }catch(PDOException $e){
           echo $e->getMessage();
       }
}
}elseif (isset($_POST['update_event']) && $_POST['update_event'] == "date") {
    //update check pdo
    try {
        //update database
        $stmt = $conn->prepare("UPDATE copo_plugin SET start_date=?,end_date=? where id=? ");
        $stmt->execute(array($_POST['start_date'], $_POST['end_date'], $_POST['id']));
        //update successfully message
        echo "UPDATE successfully";
    } catch (PDOException $e) {
        //update error message
        echo  $e->getMessage();
    }
} elseif (isset($_POST['update_form']) && $_POST['update_form'] == "form") {
    $stmt = $conn->prepare("SELECT * FROM copo_plugin where id=?");
    $stmt->execute(array($_POST['id']));
    $row = $stmt->fetch();
?>
    <form class="form-update-event">
        <div class="row">
            <div class="col-sm">
                <div class="form-group">
                    <label for="input">title</label>
                    <input type="text" class="form-control" id="input" value="<?php echo $row['title']; ?>" name="title" require />
                </div>
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <label for="input">color</label>
                    <input type="color" class="form-control" id="input" value="<?php echo $row['color']; ?>" name="color" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <div class="form-group">
                    <label for="start-date">start</label>
                    <input type="date" class="form-control" id="start-date" value="<?php echo $row['start_date']; ?>" name="start_date" require />
                </div>
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <label for="end-date">end</label>
                    <input type="date" class="form-control" id="end-date" value="<?php echo $row['end_date']; ?>" name="end_date" require />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <div class="form-group">
                    <label for="textarea-description">decription</label>
                    <textarea class="form-control" id="textarea-description" name="description" rows="5"><?php echo $row['description']; ?></textarea>
                    <?php //wp_editor( '', 'textarea-description' ); 
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="cover">cover</label>
                    <?php if (!empty($row['cover'])) { ?>
                        <input type="hidden" name="old_cover" value="<?php echo $row['cover']; ?>">
                    <?php } ?>
                    <input type="file" onchange="preview_show('cover-preview-update',true)" class="form-control" id="cover" name="cover" />
                </div>
            </div>
            <div class="col-sm">
                <img id="cover-preview-update" <?php if (!empty($row['cover'])) {
                                                    echo "src=" . plugins_url('files/' . $row['cover'], __FILE__);
                                                } ?> style="width:200px;height:200px;<?php if (empty($row['cover'])) {
                                                                                                                                                                                        echo "display:none";
                                                                                                                                                                                    } ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <div class="form-group">
                    <input type="hidden" name="update_event_send" value="<?php echo $row['id']; ?>" />
                    <button type="button" class="btn btn-success w-50" id="update-event-btn" onclick="form_send('.form-update-event','<?php echo plugins_url('/', __FILE__) ?>','update-event-btn')">update event</button>
                </div>
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <button type="button" class="btn btn-danger w-50" id="delete-event-btn" onclick="delete_event('<?php echo $row['id']; ?>','<?php echo plugins_url('/', __FILE__) ?>')">delete event</button>
                </div>
            </div>
        </div>
    </form>
<?php
} else {
    //get event from database using user and event type
    $stmt = $conn->prepare("SELECT * FROM copo_plugin");
    $stmt->execute();
    //check isset data
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            //convert data to array
            $event[] = array(
                'id' => $row['id'],
                'title' => $row['title'],
                'start' => $row['start_date'],
                'end' => $row['end_date'],
                'color' => $row['color']
            );
        }
        // convert array data to json and print
        print_r(json_encode($event));
    } else {
        echo "[]";
    }
}
