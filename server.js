const express = require("express");
const fs = require("fs");
const path = require("path");
const app = express();
const PORT = process.env.PORT || 3000;

app.use(express.json());
app.use(express.static(__dirname)); // Cho phép đọc HTML/CSS/JS

const DATA_FILE = path.join(__dirname, "data.json");

// Đọc dữ liệu từ file hoặc tạo file mới
function loadData() {
    if (!fs.existsSync(DATA_FILE)) {
        const initData = { Level: [], Entity: [], Other: [] };
        fs.writeFileSync(DATA_FILE, JSON.stringify(initData, null, 2));
        return initData;
    }
    return JSON.parse(fs.readFileSync(DATA_FILE, "utf8"));
}

// Lưu dữ liệu vào file
function saveData(data) {
    fs.writeFileSync(DATA_FILE, JSON.stringify(data, null, 2));
}

// API lấy dữ liệu
app.get("/api/data", (req, res) => {
    res.json(loadData());
});

// API lưu dữ liệu
app.post("/api/save", (req, res) => {
    const newData = req.body;
    saveData(newData);
    res.json({ message: "Lưu thành công" });
});

// Chạy server
app.listen(PORT, () => {
    console.log(`Server chạy tại http://localhost:${PORT}`);
});
