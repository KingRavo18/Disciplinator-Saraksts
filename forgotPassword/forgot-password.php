<div id="forgotPasswordFullArea">
    <div id="forgotPasswordArea">
        <div id="ForgotPasswordCloseButtonArea">
            <button id="CloseButton" onclick="HideForgotPasswordArea()">&#x2715;</button>
        </div>
        <div id="ForgotPasswordTitle">
            <h2>Atiestatiet Savu Paroli</h2>
        </div>
        <form action="../forgotPassword/forgot_password.php" method="POST">
            <div class="ForgotPasswordInfo">
                <p>Ievadiet save e-pasta adresi un mēs nosūtīsim jums saiti lai atiestatītu jūsu paroli.</p>
            </div>
            <div class="ForgotPasswordInput">
                <input type="email" id="email" name="email" placeholder="epasts" required>
            </div>
            <div id="ForgotPassowrdConfirmationButton">    
                <button type="submit">Sūtīt Saiti</button>
            </div>
        </form>
    </div>
</div>