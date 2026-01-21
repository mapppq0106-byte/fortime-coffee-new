CREATE DATABASE IF NOT EXISTS book_store;
USE book_store;

CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100),
    description TEXT,
    image_url VARCHAR(255),
    price DECIMAL(10, 2)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO books (title, author, description, image_url) VALUES 
('5 Múi Giờ, 10 Tiếng Bay', 'Nhật Ký Yêu Xa', 'Câu chuyện về tình yêu xa đầy cảm xúc.', 'book1.jpg'),
('Gói Nỗi Buồn Lại Và Ném Đi', 'An Nhiên', 'Cuốn sách giúp bạn vượt qua những ngày khó khăn.', 'book2.jpg'),
('Hẹn Nhau Ở Một Cuộc Đời Khác', 'Gari', 'Những tản văn nhẹ nhàng về cuộc sống.', 'book3.jpg',),
('Chạng Vạng', 'Tsuko', 'Ma cà rồng trong bóng tối.', 'book4.jpg');