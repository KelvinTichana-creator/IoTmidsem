<?php
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>Water Level Monitoring</title>
    <link rel="stylesheet" href="../style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.21/datatables.min.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
    <!-- Boxicons CDN Link -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style type="text/css">
        body {
            overflow-y: hidden;
            /* Hide vertical scrollbar */
            overflow-x: hidden;
            /* Hide horizontal scrollbar */
        }

        .inline {
            display: inline-block;
            font-size: 25px;
            font-weight: 500;
        }

        .display {
            justify-content: center;
        }

        .circle {
            border: 1px black;
            width: 250px;
            padding: 2px;
            background-color: #fff;
            margin-right: 2em;
            box-shadow: 0 1px 1px rgb(0 0 0 / 10%);
        }

        .home-content {
            padding: 0 50px;
        }

        .text {
            display: flex;
            justify-content: center;
        }

        .btn-color1{
            background-color: cyan;
        }

        .btn-color2{
            background-color: red;
        }

    </style>
</head>

<body>
    <section class="">
                <div class="home-content">
                    <div class="display">
                        <div class="info">
                        <div class="inline"> <button onclick="ShowAllUsers1()">TANK 1</button> </div>
                        </div>
                    </div>
                    <div class="display">
                        <div class="circle">
                            <div class="text">Auto:</div>
                            <div class="">
                                <a class="btn btn-color1 m-1 float-right" onclick="openUrl('http://192.168.137.155/AUTO', 'hi')">
                                    <i class="fa"></i>&nbsp;&nbsp;&nbsp;&nbsp;ON&nbsp;&nbsp;&nbsp;</a>
                            </div>
                        </div>
                        <div class="circle">
                            <div class="text">Manual:</div>

                            <div class="">
                                <a class="btn btn-color2 m-1 float-right" onclick="openUrl('http://192.168.137.155/OFF', 'hi')">
                                    <i class="fa"></i>&nbsp;&nbsp;&nbsp;&nbsp;OFF&nbsp;&nbsp;&nbsp;</a>
                            </div>

                            <div class="">
                                <a class="btn btn-color1 m-1 float-right" onclick="openUrl('http://192.168.137.155/ON', 'hi')">
                                    <i class="fa"></i>&nbsp;&nbsp;&nbsp;&nbsp;ON&nbsp;&nbsp;&nbsp;</a>
                            </div>
                        </div>
                    </div>
                    <div class="display">
                        <div class="info">
                            <div class="inline">Water Level: </div>
                            <div class="inline" id="dbdata"></div>
                        </div>
                    </div>
                </div>


                <div class="home-content">
                    <div class="display">
                        <div class="info">
                        <div class="inline"> <button onclick="ShowAllUsers2()">TANK 2</button> </div>
                        </div>
                    </div>
                    <div class="display">
                        <div class="circle">
                            <div class="text">Auto:</div>
                            <div class="">
                                <a class="btn btn-color1 m-1 float-right" onclick="openUrl('http://192.168.137.155/AUTO', 'Hi')">
                                    <i class="fa"></i>&nbsp;&nbsp;&nbsp;&nbsp;ON&nbsp;&nbsp;&nbsp;</a>
                            </div>
                        </div>
                        <div class="circle">
                            <div class="text">Manual:</div>

                            <div class="">
                                <a class="btn btn-color2 m-1 float-right" onclick="openUrl('http://192.168.137.155/OFF', 'Hi')">
                                    <i class="fa"></i>&nbsp;&nbsp;&nbsp;&nbsp;OFF&nbsp;&nbsp;&nbsp;</a>
                            </div>

                            <div class="">
                                <a class="btn btn-color1 m-1 float-right" onclick="openUrl('http://192.168.137.155/ON', 'Hi')">
                                    <i class="fa"></i>&nbsp;&nbsp;&nbsp;&nbsp;ON&nbsp;&nbsp;&nbsp;</a>
                            </div>
                        </div>
                    </div>
                    <div class="display">
                        <div class="info">
                            <div class="inline">Water Level: </div>
                            <div class="inline" id="dbdata2"></div>
                        </div>
                    </div>


                </div>



        <div class="ml-5 mr-5">
            <hr class="my-1">
            <div class="table-responsive" id="showUser">

            </div>
        </div>

    </section>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.21/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script type="text/javascript">
        var intervalId = window.setInterval(function() {
            updateByAJAX_dbData()
        }, 1000);

        var intervalId = window.setInterval(function() {
            updateByAJAX_dbData2()
        }, 1000);

        function openUrl(url, title) {
            if (!title) {
                title = 'Just another window';
            }
            var x = window.open(url, title, 'toolbar=1,location=1,directories=1,status=1,menubar=1,scrollbars=1,resizable=1');

            x.blur();
            setTimeout(function() {
                x.close();
            }, 300);
        }


        function updateByAJAX_dbData() {
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                document.getElementById("dbdata").innerText = this.responseText;
            }
            xhttp.open("GET", "/IoT/marshall/retrieve.php?id=1");
            xhttp.send();
        }

        function updateByAJAX_dbData2() {
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                document.getElementById("dbdata2").innerText = this.responseText;
            }
            xhttp.open("GET", "/IoT/marshall/retrieve.php?id=2");
            xhttp.send();
        }

        function ShowAllUsers1() {
                $.ajax({
                    url: ["../controller/waterlevel-entries.php?id=1"],
                    type: "POST",
                    data: {
                        action: "view"
                    },
                    success: function(response) {
                        // console.log(response);
                        $("#showUser").html(response);
                        $("table").DataTable();
                    }
                });
        }

        function ShowAllUsers2() {
                $.ajax({
                    url: ["../controller/waterlevel-entries.php?id=2"],
                    type: "POST",
                    data: {
                        action: "view"
                    },
                    success: function(response) {
                        $("#showUser").html(response);
                        $("table").DataTable();
                    }
                });
        }

        $(document).ready(function() {


            // insert ajax request
            $("#insert").click(function(e) {
                if ($("#form-data")[0].checkValidity) {
                    e.preventDefault();
                    $.ajax({
                        url: ["../controller/waterlevel-entries.php"],
                        type: "POST",
                        data: $("#form-data").serialize() + "&action=insert",
                        success: function(response) {

                            Swal.fire({
                                title: 'User added successfully!',
                                showConfirmButton: false,
                                type: 'success',
                                icon: 'success',
                                timer: 500,
                                timerProgressBar: true,
                            })

                            $("#addModal").modal("hide");
                            $("#form-data")[0].reset();
                            ShowAllUsers();

                        }
                    });
                }
            });


            // Edit user
            $("body").on("click", ".editBtn", function(e) {
                // console.log("working");
                e.preventDefault();
                edit_id = $(this).attr("id");
                $.ajax({
                    url: "../controller/waterlevel-entries.php",
                    type: "POST",
                    data: {
                        edit_id: edit_id
                    },
                    success: function(response) {
                        console.log(response);
                        data = JSON.parse(response);
                        // console.log(data);
                        $("#id").val(data.id);
                        $("#username").val(data.username);
                        $("#email").val(data.email);
                        $("#phone_number").val(data.phone_number);
                        $("#credit").val(data.credit);
                        $("#farm").val(data.farm);
                    }
                });
            });

            // Update ajax request
            $("#update").click(function(e) {
                if ($("#edit-form-data")[0].checkValidity) {
                    e.preventDefault();
                    $.ajax({
                        url: ["../controller/waterlevel-entries.php"],
                        type: "POST",
                        data: $("#edit-form-data").serialize() + "&action=update",
                        success: function(response) {

                            Swal.fire({
                                title: 'User updated successfully!',
                                showConfirmButton: false,
                                type: 'success',
                                icon: 'success',
                                timer: 800,
                                //timerProgressBar: true,
                            })

                            $("#editModal").modal("hide");
                            $("#edit-form-data")[0].reset();
                            ShowAllUsers();
                        }
                    });
                }
            });


            // Delete ajax request 
            $("body").on("click", ".delBtn", function(e) {
                e.preventDefault();
                var tr = $(this).closest("tr");
                del_id = $(this).attr("id");
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../controller/waterlevel-entries.php',
                            type: 'POST',
                            data: {
                                del_id: del_id
                            },
                            success: function(response) {
                                tr.css('background-color', '#ff6666');
                                Swal.fire({
                                    title: 'User deleted successfully!',
                                    showConfirmButton: false,
                                    type: 'success',
                                    icon: 'success',
                                    timer: 900,
                                    //timerProgressBar: true,
                                })
                                ShowAllUsers();
                            }
                        });

                    }
                })

            });


            // Show users detail  page
            $("body").on("click", ".infoBtn", function(event) {
                event.preventDefault();
                info_id = $(this).attr("id");
                $.ajax({
                    url: "../controller/waterlevel-entries.php",
                    type: "POST",
                    data: {
                        info_id: info_id
                    },
                    success: function(response) {
                        //console.log(response);
                        data = JSON.parse(response);
                        Swal.fire({
                            title: '<strong>User info : ID ' + data.id + '</strong>',
                            type: 'info',
                            html: '<b>Username:</b> ' + data.username + '<br>' +
                                '<b>Email:</b> ' + data.email + '<br>' +
                                '<b>Phone number:</b> ' + data.phone_number + '<br>' +
                                '<b>Credit:</b> ' + data.credit + '<br>' +
                                '<b>Farm:</b> ' + data.farm
                        })
                    }
                });
            });

        })
    </script>

</body>

</html>