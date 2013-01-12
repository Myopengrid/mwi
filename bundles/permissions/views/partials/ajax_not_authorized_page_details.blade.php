<div id="page-details">
    <fieldset>
        <legend>Error 401 - Unauthorized</legend>
        <p>
            <?php $messages = array('You got pulled over!', 'You don\'t have the power!', 'You shall not pass!'); ?>

            <h2><?php echo $messages[mt_rand(0, 2)]; ?></h2>

            <hr>

            <h3>What does this mean?</h3>

            <p>
                {{ Auth::user()->username }}, your access was denied, you belong to the group [{{ Auth::user()->group->name }}], in order to access this area your group must be granted privileges.
            </p>
    </p> 
    </fieldset>
</div>
    