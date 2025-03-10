import React from 'react';
import {
  Box,
  Toolbar,
  AppBar,
  List,
  ListItem,
  ListItemButton,
  ListItemIcon,
  ListItemText,
  Typography,
  Paper,
  TextField,
  Button,
  Divider,
  Grid,
  IconButton,
  Tooltip,
  styled,
} from '@mui/material';
import {
  Menu,
  Home,
  Settings,
  Add,
  Search,
  DesignServices,
  Description,
  Info,
  AttachFile,
} from '@mui/icons-material';

// 自定义滚动区域样式
const ScrollableContent = styled(Box)(({ theme }) => ({
  flexGrow: 1,
  overflowY: 'auto',
  padding: theme.spacing(3),
}));

// 自定义侧边栏样式
const Sidebar = styled(Paper)(({ theme }) => ({
  width: 80, // 设置侧边栏宽度
  padding: theme.spacing(2),
}));

const App = () => {
  // ... (之前的handleDrawerToggle函数和drawer变量移除)

  return (
    <Box sx={{ display: 'flex', flexDirection: 'column', height: 'calc(100vh - 64px)' }}>
      {/* 顶部 AppBar */}
      <AppBar position="static">
        <Toolbar>
          <Typography variant="h6" component="div" sx={{ flexGrow: 1 }}>
            No. 100884 100684-课程标准审批單-总务处/信息中心-001
          </Typography>
          <Typography variant="body2">
            主办(第1步:专业负责人发起)
          </Typography>
        </Toolbar>
      </AppBar>

      {/* 内容区域 (包括侧边栏和中间滚动区域) */}
      <Box sx={{ display: 'flex', flexGrow: 1 }}>
        {/* 左侧固定侧边栏 */}
        <Sidebar sx={{ borderRadius: 0 }}>
          <List>
            {['表单', '附件', '会签', '流程'].map((text, index) => (
              <ListItem key={text} disablePadding>
                <ListItemButton sx={{ flexDirection: 'column', alignItems: 'center' }}>
                  <ListItemIcon sx={{ minWidth: 'auto', mb: 0 }}>
                    {index % 2 === 0 ? <Home /> : <Settings />}
                  </ListItemIcon>
                  <ListItemText primary={text} sx={{ textAlign: 'center', whiteSpace: 'nowrap', wordWrap: 'break-word', }} />
                </ListItemButton>
              </ListItem>
            ))}
          </List>
        </Sidebar>

        {/* 中间滚动区域 */}
        <ScrollableContent>
          <Paper sx={{ padding: 2 }}>
            <Grid container spacing={2}>
              {/* ... (中间内容保持不变) */}
            </Grid>
          </Paper>
        </ScrollableContent>
      </Box>

      {/* 底部固定 Toolbar */}
      <AppBar position="static" color="default" sx={{ top: 'auto', bottom: 0 }}>
        <Toolbar>
          <Button variant="contained" size="small" sx={{ ml: 'auto' }}>
            转文下一步
          </Button>
          <Button variant="outlined" size="small" sx={{ ml: 2 }}>
            保存
          </Button>
          <Button variant="outlined" size="small" sx={{ ml: 2 }}>
            保存退回
          </Button>
        </Toolbar>
      </AppBar>
    </Box>
  );
};

export default App;