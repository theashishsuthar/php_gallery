<div class="container-fluid">

                <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?php

$user = User::find_user_by_id(2);
// $user->delete();

// $user = new User();

$user->username="newashish";
$user->password ="1234";
$user->first_name="Ashihs";
$user->last_name = "Gajjar";


$user->update();

?>
                Blank Page
                <small>Subheading</small>
            </h1>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
                </li>
                <li class="active">
                    <i class="fa fa-file"></i> Blank Page
                </li>
            </ol>
        </div>
                </div>
                <!-- /.row -->

            </div>
