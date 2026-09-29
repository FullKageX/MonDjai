<form method="POST" action="/admin/login">
    @csrf
    <div>
        <label for="telephone">telephone:</label>
        <input type="telephone" name="telephone" required>
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
    </div>
    <button type="submit">Login</button>
</form>