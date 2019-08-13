<div class="modal fade" id="record-management" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">


        <div class="modal-content">

            <div class="modal-header no-border">

                <div class="tab-list">

                    <a class="btn active" href="#contact-information" data-toggle="tab">連絡先</a>
                    <a class="btn" href="#basic-information" data-toggle="tab">基本情報</a>
                    <a class="btn" href="#attention-information" data-toggle="tab">良い情報</a>

                </div>

            </div>

        </div>


        <!-- Tab panes -->
        <div class="tab-content">

            <div role="tabpanel" class="tab-pane active" id="contact-information">
                <div class="modal-content">
                    <div class="modal-body">
                        @includeIf('hospital.partials.contract-form')
                    </div>
                </div>
            </div>


            <div role="tabpanel" class="tab-pane" id="basic-information">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3>Dummy text - Basic Information</h3>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </div>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane" id="image-information">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3>Dummy text - Image Information</h3>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </div>
                </div>
            </div>


            <div role="tabpanel" class="tab-pane" id="attention-information">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3>Dummy text - Attention information</h3>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </div>
                </div>
            </div>

        </div>


    </div>
</div>