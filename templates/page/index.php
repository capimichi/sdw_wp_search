<div class="wrap">
    <h1>WooCommerce Search Reindex</h1>

    <!--    <form method="post" action="">-->
    <table class="form-table">
        <tbody>
        <tr>
            <th scope="row"><label for="fs_secret_key">Private Key</label></th>
            <td><input name="fs_secret_key" type="text" id="fs_secret_key" value="<?php echo $secret_key; ?>"
                       class="regular-text"></td>
        </tr>
        <tr>
            <th scope="row"><label for="fs_public_key">Public Key</label></th>
            <td><input name="fs_public_key" type="text" id="fs_public_key" value="<?php echo $public_key; ?>"
                       class="regular-text"></td>
        </tr>
        <tr>
            <th scope="row"><label for="fs_logout">Logout</label></th>
            <td>
                <button class="fs_logout button">Logout</button>
            </td>
        </tr>
        </tbody>
    </table>

    <!--        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"-->
    <!--        value="Save"></p>-->
    <!--    </form>-->
</div>