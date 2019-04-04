<style>
    .widget-user-header {
        background-position: center center;
        background-size: cover
    }
</style>

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 mt-3">
                
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header text-white" style="background-image: url('./img/cover.jpg');">
                <h3 class="widget-user-username">Elizabeth Pierce</h3>
                <h5 class="widget-user-desc">Web Designer</h5>
              </div>
              <div class="widget-user-image">
                <img class="img-circle" :src="getProfilePhoto()" alt="User Avatar">
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">3,200</h5>
                      <span class="description-text">SALES</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">13,000</h5>
                      <span class="description-text">FOLLOWERS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4">
                    <div class="description-block">
                      <h5 class="description-header">35</h5>
                      <span class="description-text">PRODUCTS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
            
            </div>
        </div>

        <div class="row justify-content-center">
            
            <div class="col-sm-12 mt-3">
                
                <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active show" href="#activity" data-toggle="tab">Activity</a></li>
                  <!-- <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li> -->
                  <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active show" id="activity">
                    <!-- Post -->
                    <h4>Display User Activity</h4>
                    <!-- /.post -->
                  </div>
                  <!-- /.tab-pane -->
                  

                  <div class="tab-pane" id="settings">
                    <form class="form-horizontal" method="POST" enctype="multipart/form-data">
                      <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">Name</label>

                        <div class="col-sm-10">
                          <input type="text" v-model="form.name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" id="inputName" placeholder="Name">
                          <has-error :form="form" field="name"></has-error>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                        <div class="col-sm-10">
                          <input type="email" v-model="form.email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" id="inputEmail" placeholder="Email">
                          <has-error :form="form" field="email"></has-error>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="inputExperience" class="col-sm-2 control-label">Bio</label>

                        <div class="col-sm-10">
                          <textarea class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }" id="inputExperience" placeholder="Experience" v-model="form.bio"></textarea>
                          <has-error :form="form" field="bio"></has-error>
                        </div>
                      </div>


                      <div class="form-group">
                        <label for="inputPhoto" class="col-sm-2 control-label">Profile Picture</label>

                        <div class="col-sm-10">
                          <input type="file" name="inputPhoto" @change="updateProfileImage" id="inputPhoto">
                        </div>
                      </div>


                      <div class="form-group">
                        <label for="inputPassport" class="col-sm-2 control-label">Password</label>

                        <div class="col-sm-10">
                          <input class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" id="inputPassport" placeholder="Password" type="password" v-model="form.password">
                          <has-error :form="form" field="password"></has-error>
                        </div>
                      </div>
                      
                      
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button @click.prevent='updateInfo' type="submit" class="btn btn-danger">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>

            </div><!--col-->

        </div><!--row-->


    </div>
</template>

<script>
    export default {
        data(){

            return {
                form: new Form({
                    id:'',
                    name: '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: ''
                })
            }

        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {

            getProfilePhoto(){

                return "img/profile/"+this.form.photo;


            },

            updateProfileImage(e) {
                // console.log("uploading ...");console.log("uploading ...");

                let file = e.target.files[0];
                let reader = new FileReader();
                
                if(file['size'] < 2111775) {
                    reader.onloadend = (file)=> {
                        this.form.photo = reader.result;
                    }

                    reader.readAsDataURL(file);

                } else {
                    Swal.fire(
                          'Failed!',
                          'You are uploading Large File(>2MB)',
                          'warning'
                        )
                }

            },

             updateInfo(){
                this.$Progress.start();
                this.form.put('api/profile')
                .then(()=>{
                    this.$Progress.finish();
                    Toast.fire({
                      type: 'success',
                      title: 'User Profile Updated successfully'
                    })

                    Fire.$emit('AfterProfileUpdate');
                })
                .catch(() => {
                    this.$Progress.fail();
                });

                //console.log("updating ...");
            }
        },
        created() {
            axios.get('api/profile').then(({data})=>(this.form.fill(data)));
            Fire.$on('AfterProfileUpdate',()=>{
                this.$forceUpdate();
            });
        }
    }
</script>
