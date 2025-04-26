import React, { useState, useEffect, Fragment, ChangeEvent } from 'react';
import axios from 'axios';
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
import { Search, DesignServices, Description, } from '@mui/icons-material';
import CircularProgress from '@mui/material/CircularProgress'
import { authConfig, defaultConfig } from 'src/configs/auth'
import { useRouter } from 'next/router'
import StartModel from 'src/views/Workflow/Start'


const NewModel = () => {
  interface WorkItem {
    FlowName: string;
    FormId: string;
    Memo: string;
    FormGroup: string;
    FlowId: string
  }

  const [selectedCategory, setSelectedCategory] = useState('资产');
  const [currentWorkItems, setCurrentWorkItems] = useState<WorkItem[]>([]);
  const [allWorkItems, setAllWorkItems] = useState<{[key: string]: WorkItem[]}>({});
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [pageModel, setPageModel] = useState('New');
  const [flowId, setFlowId] = useState<string>('');

  useEffect(() => {
    const fetchWorkItems = async () => {
      try {
        const storedToken = window.localStorage.getItem(defaultConfig.storageTokenKeyName)!
        const response = await axios.get(authConfig.backEndApiHost + 'workflow/start.php', {
          params: { action: 'MyNewWorkflow' },
          headers: { Authorization: storedToken}
        });
        const data = response.data;
        setAllWorkItems(data.data);
        const DataKeys = Object.keys(data.data);
        if(DataKeys && DataKeys.length > 0) {
          setCurrentWorkItems(data.data[DataKeys[0]] || []);
        }
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

  const handleSearch = async (event: ChangeEvent<HTMLInputElement>) => {
    const searchTerm = event.target.value;
    
    try {
      const storedToken = window.localStorage.getItem(defaultConfig.storageTokenKeyName)!
      const response = await axios.get(`${authConfig.backEndApiHost}workflow/start.php`, {
        params: { action: 'SearchWorkflow', keyword: searchTerm },
        headers: { Authorization: storedToken}
      });
      const data = response.data;
      setAllWorkItems(data.data);
      const DataKeys = Object.keys(data.data);
      if(DataKeys && DataKeys.length > 0) {
        setCurrentWorkItems(data.data[DataKeys[0]] || []);
      }
    } catch (err) {
      console.error('搜索出错:', err);
    }
  };

  const handleActionClick = (action: string) => {
    // 处理操作按钮点击逻辑
    console.log('点击操作:', action);
  };

  const router = useRouter()

  const handleReturnButton = () => {
    setFlowId('')
    router.push('my')
  }

  return (
    <Fragment>
      {pageModel == "New" && (
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
                      ml: 2,
                      width: '100%',
                      fontSize: 18
                    }}
                  >
                    新建工作
                  </Typography>
                </Grid>
                <Grid container item xs={8} alignItems="center">
                  <Grid item xs>
                    <TextField
                      label="请输入流程名称"
                      variant="outlined"
                      size="small"
                      fullWidth
                      onChange={handleSearch}
                      InputProps={{
                        endAdornment: <Search />,
                      }}
                      sx={{ mb: 1, my: 2, pr: 2 }}
                    />
                  </Grid>
                  <Grid item>
                    <Button 
                      size="small" 
                      onClick={() => {router.push('my')}} 
                      variant="contained" 
                      sx={{ mr: 2 }}
                    >
                      我的工作
                    </Button>
                  </Grid>
                </Grid>

              </Grid>
              <Grid container spacing={2}>
                <Grid item xs={2}>
                  <Paper sx={{ height: 'calc(100vh - 150px)', backgroundColor: 'background.paper' }}>
                    <List>
                      {allWorkItems && Object.keys(allWorkItems).map((category, index) => (
                        <ListItemButton
                          key={category}
                          selected={selectedCategory === category}
                          onClick={() => handleCategoryClick(category)}
                          onMouseEnter={() => handleCategoryClick(category)}
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
                            <ListItem sx={{my: 1, py: 0}} >
                              <Grid container alignItems="center" spacing={2}>
                                <Grid item xs={4}>
                                  <ListItemText  sx={{my: 1}}
                                    primary={item.FlowName} 
                                    secondary={item.Memo || '暂无描述'} 
                                  />
                                </Grid>
                                <Grid item xs={4}>
                                  <Grid container justifyContent="flex-start">
                                    <Box display="flex" justifyContent="center">
                                      <Tooltip title="流程设计" sx={{borderRadius: 1}}>
                                        <IconButton onClick={() => handleActionClick('流程设计')}>
                                          <DesignServices />
                                          <Typography sx={{ color: 'text.secondary'}}>流程设计</Typography>
                                        </IconButton>
                                      </Tooltip>
                                      <Tooltip title="流程表单" sx={{borderRadius: 1}}>
                                        <IconButton onClick={() => handleActionClick('流程表单')}>
                                          <Description />
                                          <Typography sx={{ color: 'text.secondary'}}>流程表单</Typography>
                                        </IconButton>
                                      </Tooltip>
                                    </Box>
                                  </Grid>
                                </Grid>
                                <Grid item xs={4}>
                                  <Grid container justifyContent="flex-end">
                                    <Button 
                                      size="small" 
                                      onClick={()=>{
                                        setFlowId(item.FlowId)
                                        setPageModel('Start')
                                      }}
                                      variant='contained' 
                                      sx={{ px: 5.5 }}
                                    >
                                      {'新建工作'}
                                    </Button>
                                  </Grid>
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
      )}
      {pageModel == "Start" && (
        <StartModel FlowId={flowId} handleReturnButton={handleReturnButton} flowRecord={null} />
      )}
    </Fragment>
  );
};

export default NewModel;
