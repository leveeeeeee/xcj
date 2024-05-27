<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>个人主页</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color:aliceblue;
      color: #333;
      margin: 0;
    }
    
    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
    }
    
    h1 {
      text-align: center;
      margin-bottom: 20px;
    }
    
    .section {
      margin-bottom: 40px;
    }
    
    .section-title {
      font-size: 24px;
      margin: 0 0 20px;
    }
    
    table {
      width: 100%;
      border-collapse: collapse;
    }
    
    th, td {
      padding: 10px;
      border: 1px solid #ccc;
    }
    
    th {
      background-color: #f1f1f1;
      font-weight: bold;
      text-align: left;
    }
    
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    
    #filter {
      margin-bottom: 10px;
    }
    #historyTable{
        background-color: #FFFFFF;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Hello,用户名</h1>
    
    <!-- 历史记录部分 -->
    <div class="section">
      <h2 class="section-title">历史记录</h2>
      <div>
        <label for="filter">筛选：</label>
        <select id="filter">
          <option value="all">全部</option>
          <option value="mastered">已掌握</option>
          <option value="unmastered">未掌握</option>
        </select>
      </div>
      <table id="historyTable">
        <thead>
          <tr>
            <th>名称</th>
            <th>公式</th>
            <th>知识范围</th>
            <th>学习阶段</th>
            <th>掌握情况</th>
          </tr>
        </thead>
        <tbody>
          <!-- 这里是动态生成的历史记录内容，你可以根据自己的需求填充表格数据 -->
          <tr>
            <td>内容1</td>
            <td>公式1</td>
            <td>知识范围1</td>
            <td>学习阶段1</td>
            <td>已掌握</td>
          </tr>
          <tr>
            <td>内容2</td>
            <td>公式2</td>
            <td>知识范围2</td>
            <td>学习阶段2</td>
            <td>未掌握</td>
          </tr>
          <!-- 其他历史记录条目 -->
        </tbody>
      </table>
    </div>
  </div>

  <script>
    // JavaScript 代码可以在这里添加，用于实现历史记录筛选功能等
    const filter = document.getElementById('filter');
    const historyTable = document.getElementById('historyTable');
    
    filter.addEventListener('change', function() {
      const value = filter.value;
      const rows = historyTable.querySelectorAll('tbody tr');
      
      rows.forEach(row => {
        if (value === 'all') {
          row.style.display = 'table-row';
        } else if (value === 'mastered' && row.textContent.includes('已掌握')) {
          row.style.display = 'table-row';
        } else if (value === 'unmastered' && row.textContent.includes('未掌握')) {
          row.style.display = 'table-row';
        } else {
          row.style.display = 'none';
        }
      });
    });
  </script>
</body>
</html>
