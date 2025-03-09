import React, { useState, useEffect, Fragment, ChangeEvent } from 'react';
import {
  Box,
  TextField,
  Button,
  List,
  ListItem,
  ListItemButton,
  ListItemText,
  ListItemIcon,
  Divider,
  Grid,
  Paper,
  IconButton,
  Tooltip,
  Typography,
} from '@mui/material';
import {
  Add,
  Search,
  DesignServices,
  Description,
  Info,
  Folder,
} from '@mui/icons-material';
import CircularProgress from '@mui/material/CircularProgress'
import { authConfig, defaultConfig } from 'src/configs/auth'

import Link from 'next/link'


const WorkList = () => {
  interface WorkItem {
    FlowName: string;
    FormId: string;
    Memo: string;
    FormGroup: string;
  }

  const [selectedCategory, setSelectedCategory] = useState('常用工作');
  const [currentWorkItems, setCurrentWorkItems] = useState<WorkItem[]>([]);
  const [allWorkItems, setAllWorkItems] = useState<{[key: string]: WorkItem[]}>({});
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchWorkItems = async () => {
      try {
        const response = await fetch(authConfig.backEndApiHost + 'workflow/start.php?action=MyNewWorkflow');
        if (!response.ok) {
          throw new Error('Failed to fetch workflows');
        }
        const data = await response.json();
        setAllWorkItems(data.data);
        setCurrentWorkItems(data.data['资产'] || []);
      } catch (err) {
        setError(err instanceof Error ? err.message : 'Failed to fetch workflows');
      } finally {
        setLoading(false);
      }
    };

    fetchWorkItems();
  }, []);

  const handleCategoryClick = (category: string) => {
    setSelectedCategory(category);
    setCurrentWorkItems(allWorkItems[category]);
  };

  const handleSearch = (event: ChangeEvent<HTMLInputElement>) => {
    // 处理搜索逻辑
    console.log('搜索:', event.target.value);
  };

  const handleNewWork = () => {
    // 处理新建工作逻辑
    console.log('新建工作');
  };

  const handleActionClick = (action: string) => {
    // 处理操作按钮点击逻辑
    console.log('点击操作:', action);
  };

  return (
    <Box sx={{ p: 2 }}>
      {loading ? (
        <Grid item xs={12} sm={12} container justifyContent="space-around">
            <Box sx={{ mt: 6, mb: 6, display: 'flex', alignItems: 'center', flexDirection: 'column' }}>
                <CircularProgress size={45} />
                <Typography sx={{ mt: 6, mb: 6 }}>加载中...</Typography>
            </Box>
        </Grid>
      ) : error ? (
        <Box display="flex" justifyContent="center" alignItems="center" height={200}>
          <Typography color="error">{error}</Typography>
        </Box>
      ) : (
        <Fragment>
          <Grid container alignItems="center" marginBottom={2} sx={{ backgroundColor: 'background.paper', borderRadius: 1 }}>
            <Grid item xs={4}>
              <Typography
                variant="body1"
                sx={{
                  display: 'flex',
                  alignItems: 'center',
                  p: 2,
                  width: '100%',
                  fontSize: 18
                }}
              >
                新建工作
              </Typography>
            </Grid>
            <Grid item xs={8}>
              <TextField
                label="请输入流程名称"
                variant="outlined"
                size="small"
                fullWidth
                onChange={handleSearch}
                InputProps={{
                  endAdornment: <Search />,
                }}
                sx={{ my: 1}}
              />
            </Grid>
          </Grid>
          <Grid container spacing={2}>
            <Grid item xs={2}>
              <Paper sx={{ height: '100%', backgroundColor: 'background.paper' }}>
                <List>
                  {Object.keys(allWorkItems).map((category) => (
                    <ListItemButton
                      key={category}
                      selected={selectedCategory === category}
                      onClick={() => handleCategoryClick(category)}
                    >
                      <ListItemIcon>
                        <Description />
                      </ListItemIcon>
                      <ListItemText primary={category} />                      
                    </ListItemButton>
                  ))}
                </List>
              </Paper>
            </Grid>
            <Grid item xs={10}>
              <Paper>
                <List>
                  {currentWorkItems.length > 0 ? (
                    currentWorkItems.map((item, index) => (
                      <Fragment key={index}>
                        <ListItem sx={{my: 1, py: 0}}>
                          <Grid container alignItems="center" spacing={2}>
                            <Grid item xs={4}>
                              <ListItemText  sx={{my: 1}}
                                primary={item.FlowName} 
                                secondary={item.Memo || '暂无描述'} 
                              />
                            </Grid>
                            <Grid container xs={4} justifyContent="flex-start">
                              <Box display="flex" justifyContent="center">
                                <Tooltip title="流程设计" sx={{borderRadius: 1}}>
                                  <IconButton onClick={() => handleActionClick('流程设计')}>
                                    <DesignServices />
                                    <Typography>流程设计</Typography>
                                  </IconButton>
                                </Tooltip>
                                <Tooltip title="流程表单" sx={{borderRadius: 1}}>
                                  <IconButton onClick={() => handleActionClick('流程表单')}>
                                    <Description />
                                    <Typography>流程表单</Typography>
                                  </IconButton>
                                </Tooltip>
                              </Box>
                            </Grid>
                            <Grid container xs={4} justifyContent="flex-end">
                              <Button 
                                size="small" 
                                href='/' 
                                component={Link} 
                                variant='contained' 
                                sx={{ px: 5.5 }}
                              >
                                {'新建工作'}
                              </Button>
                            </Grid>
                          </Grid>
                        </ListItem>
                        {index < currentWorkItems.length - 1 && <Divider />}
                      </Fragment>
                    ))
                  ) : (
                    <Box display="flex" justifyContent="center" alignItems="center" height={200}>
                      <Typography>暂无数据</Typography>
                    </Box>
                  )}
                </List>
              </Paper>
            </Grid>
          </Grid>
        </Fragment>
      )}
    </Box>
  );
};

export default WorkList;
