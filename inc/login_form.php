<div id="id01" class="w3-modal">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">
        <div class="w3-center"><br>
            <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
        </div>

        <form class="w3-container" action="./inc/login.php" method="POST">
            <div class="w3-section">
                <label><b>아이디</b></label>
                <select id="login_id" name="login_id" onchange="changeSelectedId()">
                    <option value="" selected disabled>아이디를 선택하세요</option>  
                    <option value="sopa">소프라노A</option>
                    <option value="sopb">소프라노B</option>
                    <option value="sopbp">소프라노B+</option>
                    <option value="altoa">알토A</option>
                    <option value="altob">알토B</option>
                    <option value="tenor">테너</option>
                    <option value="bass">베이스</option>
                    <option value="operator">임역원</option>
                    <option value="editor">편집부</option>
                    <option value="accounting">회계</option>
                    <option value="admin">관리자</option>
                </select>
                <input class="w3-input w3-border w3-margin-bottom" type="text" id="username" placeholder="아이디를 입력하세요" name="user_id" required>
                <label><b>비밀번호</b></label>
                <input class="w3-input w3-border" type="password" placeholder="비밀번호를 입력하세요" name="user_pw" required>
                <button class="w3-button w3-block w3-green w3-section w3-padding" type="submit" name="submit">Login</button>
            </div>
        </form>
        <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
            <button onclick="document.getElementById('id01').style.display='none'" type="button" class="w3-button w3-red">Cancel</button>
        </div>
    </div>
</div>