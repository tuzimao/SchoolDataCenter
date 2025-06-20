---
icon: gauge-high
---

#### AI智搜

利用AI技术，可以让用户使用自然语言，来从学校的数据库中获取想要的各种结果。

例如:
1. 查询成绩
2. 查询课表
3. 查询食堂消费记录
4. 查询宿舍打卡记录
5. 查询学生请假

根据学校的具体情况进行自由配置，从指定的数据源中获得用户想要的数据，并且可以以表格，图表的形式展示给用户.

在查询的过程中，可以查询当前用户自己的数据，也可以查询全校所有的数据(此部分仅限管理员和领导).

数据安全性: 在使用AI的过程中，AI只能接触到数据表的结构信息，不会接触到数据表的具体内容，所以不会发生数据记录泄漏给AI的情况.

##### 可以根据学校业务需要，扩充支持更多的查询模块。

| <img src="./images/AIChat-21.png" > | <img src="./images/AIChat-22.png" > |
|------------------------------------------|------------------------------------------|
| <img src="./images/AIChat-23.png" > | <img src="./images/AIChat-24.png" > |
| <img src="./images/AIChat-25.png" > | <img src="./images/AIChat-25-2.png" > |
| <img src="./images/AIChat-26.png" > | <img src="./images/AIChat-27.png" > |
| <img src="./images/AIChat-27-2.png" > | <img src="./images/AIChat-28.png" > |
| <img src="./images/AIChat-29.png" > | <img src="./images/AIChat-30.png" > |

#### AI智搜配置
1 在此可以设置AI智搜所对接的本地数据库
2 每一行对应一个本地的数据表
3 可以在数据交换中配置本地数据源
4 为每一行的数据项目配置对应的提示词语，然后交由AI生成想要的SQL语句
5 由本地的程序再从数据源中查询想要的数据
6 AI模型只接触了数据表的结构信息，不会接触到具体的数据内容，所以不存在本地数据泄漏的情况
