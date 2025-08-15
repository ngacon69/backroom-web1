const express = require("express");
const path = require("path");
const app = express();
const PORT = process.env.PORT || 3000;

// Cho phép đọc tất cả file HTML/CSS/JS từ thư mục gốc
app.use(express.static(__dirname));

app.get("/", (req, res) => {
  res.sendFile(path.join(__dirname, "index.html"));
});

app.listen(PORT, () => {
  console.log(`Server chạy tại port ${PORT}`);
});
