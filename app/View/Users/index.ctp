<h1><?php echo $this->request->data['User']['name']; ?></h1>


    <ul class="nav nav-tabs">
      <li class="active"><a href="#basic" data-toggle="tab" class="active">Basic Details</a></li>
      <li><a href="#password" data-toggle="tab">Change Password</a></li>
      <li><a href="#sshkeys" data-toggle="tab">SSH keys</a></li>
    </ul>
<div class="row">
    <div class="tab-content">
      <div class="tab-pane active" id="basic">
          <div class="span6 offset3">
            <?php
            echo $this->Form->create('User', array('class' => 'well form-horizontal', 'action' => 'editdetails'));
            echo '<h3>Edit your details</h3>';
            echo $this->Bootstrap->input("name", array(
                "input" => $this->Form->text("name"),
            ));

            echo $this->Bootstrap->input("email", array(
                "input" => $this->Form->text("email"),
            ));
            echo $this->Bootstrap->button("User", array("style" => "primary", "size" => "large", 'class' => 'controls'));

            echo $this->Form->end();
            ?>
        </div>
      </div>
      <div class="tab-pane" id="password"><div class="span6 offset3">
            <?php
            echo $this->Form->create('User', array('class' => 'well form-horizontal', 'action' => 'editpassword'));
            echo '<h3>Change your password</h3>';

            echo $this->Bootstrap->input("Current password", array(
                "input" => $this->Form->password("password_current"),
            ));

            echo $this->Bootstrap->input("new password", array(
                "input" => $this->Form->password("password_new"),
            ));

            echo $this->Bootstrap->input("Confirm password", array(
                "input" => $this->Form->password("password_confirm"),
            ));
            echo $this->Bootstrap->button("Save", array("style" => "primary", "size" => "large", 'class' => 'controls'));

            echo $this->Form->end();
            ?>
        </div>
      </div>
      <div class="tab-pane" id="sshkeys">
          <div class="span6">
            <?php
            echo $this->Form->create('SshKeys', array('class' => 'well form-horizontal', 'action' => 'addkey'));
            echo '<h3>Add an SSH key</h3>';
            echo $this->Bootstrap->input("SSH key", array(
                "input" => $this->Form->textarea("key", array("class" => "input-xlarge")),
                "label" => "SSH Key",
            ));
            
            echo $this->Bootstrap->input("comment", array(
                "input" => $this->Form->text("comment", array("class" => "input-xlarge")),
            ));

            echo $this->Bootstrap->button("Add", array("style" => "primary", "size" => "large", 'class' => 'controls'));

            echo $this->Form->end();
            ?>
          </div>
          
          <div class="span6">
              <div class="well">
                  <h3>Delete an SSH key</h3>
                  <table class="table table-bordered table-striped">
                      <thead>
                          <tr>
                              <th>
                                  Comment
                              </th>
                              <th>
                                  Key
                              </th>
                              <th>
                              </th>
                          </tr>
                      </thead>
                      <tbody>
                  <?php
                    foreach ($this->request->data['SshKey'] as $key){
                        echo "<tr>";
                        echo "<td>" . $key['comment'] . "</td>";
                        echo "<td>" . $key['key'] . "</td>";
                        echo "<td>" . $this->Bootstrap->button_form("Delete", '/users/deletekey/' . $key['id'], array('style' => 'danger'), "Are you sure you want to delete the SSH key '" . $key['comment'] . "'?") . "</td>";
                        echo "</tr>";
                    }
                    ?>
                      </tbody>
                  </table>
              </div>
        </div>
      </div>
    </div>
</div>
