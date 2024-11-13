# 面試前測驗

## 1. 資料庫測驗
### 題目一
 請寫出一條 SQL 查詢語句，列出在 2023 年 5 月下訂的訂單，使用台幣 (TWD) 付款且 5 月總金 額最多的前 10 筆的 bnb_id、bnb_name，以及 5 月各旅宿總金額 (may_amount)。

 Ans：
```
SELECT o.bnb_id, b.name, SUM(o.amount) AS `may_amount`
FROM orders o
LEFT JOIN bnbs b ON o.bnb_id = b.id
WHERE 
o.created_at BETWEEN '2023-05-01 00:00:00' AND '2023-05-31 23:59:59'
AND o.currency = 'TWD'
GROUP BY o.bnb_id
ORDER BY may_amount DESC
LIMIT 10;
```

### 題目二
 在題目一的執行下，我們發現 SQL 執行速度很慢，您會怎麼去優化？請闡述您怎麼判斷與優化的方式

 Ans：使用 `EXPLAIN` 去看SQL執行的狀況，看看是否有充分利用索引，以及掃描的行數是否合理。該提的SQL查詢currency和created_at，建立該複合索引，而bnb_id也需建立索引。
 
如還是相當緩慢，可嘗試使用 Partition 或可考慮嘗試增加computed 欄位，透過日期格式轉換增加order_year_month並以stored方式儲存，且建立該索引後調整查詢sql。



## 2. API 實作測驗
### 題目一
請用 Laravel 實作一個提供成立訂單的 API

1. 此應用程式將有 2 支 endpoint
    - ###### POST /api/orders 的 API 作為輸入點
    - ###### GET /api/orders/{id} 的 API 作為查詢點
    Ans：`routes/api.php`

2. 此 API 將以以下固定的 JSON 格式輸入，並請使用 Laravel 的 FormRequest，若未使用 FormRequest 物件，不予給分
    Ans：`OrderStoreRequest`

3. 請按照以下需求實作
    - ###### POST API 將會對 POST body 作格式的檢查(請參考輸入範例)。

    - ###### POST API 為非同步處理，呼叫後會立刻回應 HTTP status 200 並觸發一個 OrderCreated 事件，將有一 listener 監聽此事件，並將訂單依照不同的 currency 存入不同的資料表。舉例來說 currency = TWD 的訂單，將會存入 orders_twd 的 資料表，而 currency = USD 將會存入 orders_usd 的資料表

    - ###### currency 欄位只會有 TWD、USD、JPY、RMB、MYR 等 5 種幣別。
    Ans：

    建立`Order/OrderStoreRequest`、`Listeners/Order/OrderStore`、`Events/Order/OrderCreated`。

    另需執行 `php artisan queue:work` 來非同步執行。

        ##Note
        Event：用於系統中的通知機制，當某件事情發生時通知其他部分的代碼。一個 Event 可以有多個 Listener
        Job：專門用於處理耗時的任務，設計用來進行隊列處理
        
        該需求本人偏向使用Job而非Event去處理，Event 是被動在尋找被呼叫的地方，而Job總是被明確地呼叫


4. 實作之類別需符合物件導向設計原則 SOLID 與設計模式。並於該此專案的README.md 說明您所使用的 SOLID 與設計模式分別為何。

  Ans：

SOLID 原則應用

        1. Single Responsibility Principle (SRP) - 單一職責原則

            OrderController: 只負責處理 HTTP 請求和回應
            OrderStoreRequest: 專注於請求驗證邏輯
            OrderService: 專門處理訂單業務邏輯
            OrderModel: 負責與資料庫table orders對接
            OrderCreatedEvent: 只負責訂單建立事件

        2. Open/Closed Principle (OCP) - 開放封閉原則

            OrderModel: 提供基礎實現，允許通過繼承擴展新的幣別訂單模型

        3. Liskov Substitution Principle (LSP) - 里氏替換原則

            所有幣別訂單模型（OrderTWD, OrderUSD 等）都繼承自 OrderModel
            子類完全遵循父類的行為規範，可以在任何使用父類的地方替換使用

        4. Interface Segregation Principle (ISP) - 接口隔離原則

            除laravel原生外，未使用

        5. Dependency Inversion Principle (DIP) - 依賴反轉原則

            所有服務類都依賴於抽象接口而不是具體實現
            使用依賴注入容器管理依賴關係
            Controller 依賴於 Service 接口而不是具體實現
---
設計模式
        

        1. Factory Pattern (工廠模式)
            OrderService 根據不同幣別創建對應的訂單模型實例

        2. Observer Pattern (觀察者模式)
            使用 Laravel 的事件系統實現非同步新增的機制
            

5. 以下所有情境皆需附上 unit test 與 feature test，並覆蓋成功與失敗之案例。
    
    Ans：`Tests/Unit/OrderServiceTest` 、 `Tests/Feature/OrderControllerTest`


6. 請使用 docker 包裝您的環境。若未使用 docker 或 docker-compose 不予給分

   Ans： `git submodule update --recursive && cd Laradock && docker compose up -d nginx mysql && cd .. && cp .env.example .env `
    ，並調整其中env DB_CONNECTION等參數。

7. 實作結果需以 GitHub 呈現。若未使用不予給分

    Ans：如此頁面
## 3. 架構測驗
### 題目一
如果由您來規劃線上通訊服務，您會怎麼設計?請提供您的設計文件，並敘述您的設計目標。

 Ans：
 # 線上通訊服務系統設計文件

## 1. 設計目標

### 1.1 核心目標
- 建立穩定可靠的即時通訊平台
- 支援多平台使用（Web、iOS、Android）
- 確保訊息傳輸的安全性與隱私
- 提供高可用性和可擴展性
- 支援大量併發用戶

### 1.2 功能目標
- 訂單通訊功能
- 客服功能
- 檔案傳輸與媒體分享
- 訊息加密
- 離線訊息儲存
- 使用者狀態管理
- 訊息同步機制

### 1.3 性能目標
- 系統可用性 99.99%
- 訊息延遲 < 100ms
- 支援單群組同時在線 10,000+ 用戶
- 支援每秒 100,000+ 訊息處理
- 檔案傳輸速度穩定

## 2. 系統架構

### 2.1 整體架構
```
[客戶端]
    │
    ├── Web 客戶端
    ├── iOS 客戶端
    └── Android 客戶端
    │
[負載均衡層]
    │
    ├── Nginx 負載均衡器
    │
[應用服務層]
    │
    ├── API 服務器 (REST API)
    ├── WebSocket 服務器
    ├── 推播通知服務
    ├── 媒體處理服務
    └── 檔案存儲服務
    │
[中間件層]
    │
    ├── Redis (快取 & 即時狀態)
    ├── ElasticSearch (搜尋引擎)
    │
[數據存儲層]
    │
    ├── MYSQL (訊息、用戶存儲)
    └── S3 (檔案存儲)
```

### 2.2 核心組件

#### 2.2.1 通訊服務器
- 使用 WebSocket 實現即時通訊
- 支援心跳檢測
- 自動重連機制
- 訊息確認機制

#### 2.2.2 訊息佇列
- 實現訊息持久化
- 處理離線訊息
- 確保訊息送達

#### 2.2.3 狀態管理
- Redis 集群存儲用戶在線狀態
- 分布式 Session 管理
- 即時狀態同步

## 3. 數據設計

### 3.1 數據庫設計

### 3.2 快取設計
```
# Redis 數據結構

# 用戶在線狀態
user:status:{user_id} -> status

# 未讀訊息計數
unread:count:{user_id} -> count

# 最近聊天列表
recent:chats:{user_id} -> [chat_id1, chat_id2, ...]

# 訂單列表
order:members:{order_id} -> [user_id1, user_id2, ...]
```

## 4. API 設計

### 4.1 RESTful API

#### 用戶相關
```
POST /api/v1/users/register
POST /api/v1/users/login
GET  /api/v1/users/profile
PUT  /api/v1/users/profile
```

#### 訊息相關
```
GET  /api/v1/messages/{chat_id}
POST /api/v1/messages
PUT  /api/v1/messages/{message_id}/status
```

#### 訂單相關
```
POST /api/v1/orders
GET  /api/v1/orders/{order_id}
PUT  /api/v1/orders/{order_id}
POST /api/v1/orders/{order_id}/members
```

### 4.2 WebSocket API
```javascript
// 連接建立
ws://domain/ws?token={jwt_token}

// 訊息格式
{
    "type": "message",
    "data": {
        "to": "user_id/order_id",
        "content": "message content",
        "messageType": "text/image/file"
    }
}
```

## 5. 安全設計

### 5.1 身份驗證
- JWT 令牌認證
- OAuth2.0 社交登錄
- 多因素認證支援

### 5.2 訊息安全
- 端到端加密
- 訊息簽名驗證
- 防重放攻擊機制

### 5.3 存儲安全
- 資料加密存儲
- 定期數據備份
- 敏感資料脫敏

## 6. 擴展性設計

### 6.1 水平擴展
- 無狀態服務設計
- 數據庫分片
- 快取集群

### 6.2 高可用性
- 服務器多區域部署
- 自動故障轉移
- 熱備份機制

## 7. 監控告警

### 7.1 系統監控
- 服務器資源監控
- 網絡延遲監控
- 服務可用性監控

### 7.2 業務監控
- 用戶活躍度監控
- 訊息處理延遲監控
- 錯誤率監控

### 7.3 告警機制
- 即時告警通知
- 告警等級分類
- 自動故障轉移觸發

## 8. 部署架構

### 8.1 容器化部署
- 使用 Docker 容器化服務
- Kubernetes 編排管理
- CI/CD 自動化部署

### 8.2 多區域部署
- 主要區域部署
- 備份區域部署
- CDN 加速

## 9. 成本估算

### 9.1 基礎設施成本
- 服務器費用
- 數據庫費用
- CDN 費用
- 帶寬費用

### 9.2 維護成本
- 運維人員
- 監控工具
- 備份存儲
- 安全防護

## 10. 開發時程

### 第一階段（3個月）
- 基礎架構搭建
- 核心功能開發
- 基本 UI/UX 實現

### 第二階段（2個月）
- 進階功能開發
- 性能優化
- 安全強化

### 第三階段（1個月）
- 系統測試
- 壓力測試
- 漸進式上線