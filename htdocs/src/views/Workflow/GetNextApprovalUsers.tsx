import { useState, useEffect, Fragment } from 'react';
import axios from 'axios';
import { Box,Typography,Button,Grid } from '@mui/material';
import { authConfig, defaultConfig } from 'src/configs/auth'
import CircularProgress from '@mui/material/CircularProgress'
import Divider from '@mui/material/Divider'
import { Autocomplete, TextField } from '@mui/material'
import toast from 'react-hot-toast'

const GetNextApprovalUsers = ({ FlowId, handleReturnButton, flowRecord, formSubmitStatus, submitCounter }: any) => {

  const [loading, setLoading] = useState(true);
  const [nextNodes, setNextNodes] = useState<any[]>([])
  const [endNode, setEndNode] = useState<boolean>(false)

  const fetchWorkItems = async () => {
    try {
        console.log("fetchWorkItems flowRecord", flowRecord, FlowId)
        const storedToken = window.localStorage.getItem(defaultConfig.storageTokenKeyName)!
        const response = await axios.post(authConfig.backEndApiHost + 'workflow/start.php?action=GetNextApprovalUsers', { FlowId, processid: flowRecord.processid, runid: flowRecord.runid }, {
            headers: {
              Authorization: storedToken,
              'Content-Type': 'application/json'
            }
          });
        const data = response.data;
        setLoading(false)
        setNextNodes(data.data)
        if(data.NextStep == "[结束]") {
            setEndNode(true)
        }
        console.log("handleToNextStep data", data)  
    } 
    catch (err) {
        console.log("1111err", err)
        setLoading(false)
        toast.error('获取下一步审批人失败', {
            duration: 2000
        })
    } 
    finally {
        setLoading(false)
    } 
  };
  
  useEffect(() => {
    
    if(flowRecord && flowRecord.processid && flowRecord.runid) {
        fetchWorkItems();
    }

  }, [FlowId, submitCounter, formSubmitStatus]);

  const [selectedUsers, setSelectedUsers] = useState<any>(null);
  const [selectedText, setSelectedText] = useState<string>('');
  const [textErrors, setTextErrors] = useState<any>(null);
  const [userErrors, setUserErrors] = useState<any>(null);
  const [newTextErrors, setNewTextErrors] = useState<string | null>(null);

  const handleToNextStep = async () => {

    const newUserErrors: any = {};
    const selectUsers: any[] = []
    nextNodes && nextNodes.map((item: any)=>{
        if (selectedUsers && selectedUsers[item.经办步骤Step] && selectedUsers[item.经办步骤Step].id) {
            selectUsers.push(item.经办步骤Step);
        }
    })
    console.log("selectedUsers", selectedUsers, nextNodes)
    if (!selectedText || selectedText.trim() === '') {
        setTextErrors("主办说明不能为空");
    }
    setUserErrors(newUserErrors);

    if(selectUsers.length == 0)      {
        nextNodes && nextNodes.map((item: any)=>{
            if (!selectedUsers || !selectedUsers[item.经办步骤Step] || selectedUsers[item.经办步骤Step].length == 0) {
                newUserErrors[item.经办步骤Step] = '下一步主办人不能为空';
            }
        })
        setNewTextErrors(null)
    }

    if(selectUsers.length > 1)      {
        setNewTextErrors('转交下一步的时候, 只能设置其中一个转交节点.')
    }

    if(selectUsers.length == 1)     {
        setNewTextErrors(null)
        try {
            const storedToken = window.localStorage.getItem(defaultConfig.storageTokenKeyName)!
            const response = await axios.post(authConfig.backEndApiHost + 'workflow/start.php?action=GoToNextStep', { FlowId, processid: flowRecord.processid, runid: flowRecord.runid, selectedText, selectedUsers }, {
                headers: {
                  Authorization: storedToken,
                  'Content-Type': 'application/json'
                }
              });
            const data = response.data;
            setLoading(false)
            if(data && data.status && data.status == 'ok') {
                toast.success(data.msg, {
                    duration: 2000
                })
                handleReturnButton()
            }
            console.log("GoToNextStep data", data)  
        } 
        catch (err) {
            setLoading(false)
            toast.error('转交下一步失败', {
                duration: 2000
            })
        } 
        finally {
            setLoading(false)
        } 
    }
  }

  const handleToEndWork = async () => {
    if (!selectedText || selectedText.trim() === '') {
        setTextErrors("主办说明不能为空");
    }

    if(selectedText && selectedText.trim() != '')   {
        try {
            const storedToken = window.localStorage.getItem(defaultConfig.storageTokenKeyName)!
            const response = await axios.post(authConfig.backEndApiHost + 'workflow/start.php?action=GoToEndWork', { FlowId, processid: flowRecord.processid, runid: flowRecord.runid, selectedText }, {
                headers: {
                    Authorization: storedToken,
                    'Content-Type': 'application/json'
                }
                });
            const data = response.data;
            setLoading(false)
            if(data && data.status && data.status == 'ok') {
                toast.success(data.msg, {
                    duration: 2000
                })
                handleReturnButton()
            }
            console.log("GoToNextStep data", data)  
        } 
        catch (err) {
            setLoading(false)
            toast.error('结束工作失败', {
                duration: 2000
            })
        } 
        finally {
            setLoading(false)
        } 
    }
  }

  console.log("formSubmitStatus", formSubmitStatus)

  return (
    <Fragment>
      {loading == true && (
        <Grid item xs={12} sm={12} container justifyContent="space-around">
          <Box sx={{ mt: 6, mb: 6, display: 'flex', alignItems: 'center', flexDirection: 'column' }}>
              <CircularProgress size={45} />
              <Typography sx={{ mt: 6, mb: 6 }}>加载中...</Typography>
          </Box>
        </Grid>
      )}
      {loading == false && formSubmitStatus && formSubmitStatus.status == 'ERROR' && (        
        <Grid item xs={12} sm={12} container justifyContent="space-around">
            <Box sx={{ mt: 6, mb: 6, display: 'flex', alignItems: 'center', flexDirection: 'column' }}>
                <Typography sx={{ mt: 6, mb: 6 }}>{formSubmitStatus.msg}</Typography>
            </Box>
        </Grid>
      )}
      {loading == false && formSubmitStatus && formSubmitStatus.status == 'OK' && (        
        <Box sx={{ display: 'flex', flexDirection: 'column' }}>
            <TextField
                sx={{ my: 2 }}
                label="主办说明"
                multiline
                rows={4}
                value={selectedText ?? ''}
                onChange={(e) => {
                    setSelectedText(e.target.value);
                    if (textErrors != '') {
                        setTextErrors(null);
                    }
                }}
                variant="outlined"
                fullWidth
                error={Boolean(textErrors && textErrors != '')} 
            />                    
            <Divider sx={{ my: 5 }} />
            {endNode == false && (
                <Fragment>
                    {nextNodes && nextNodes.map((item: any, index: number) => {
                        const selectValue = selectedUsers && selectedUsers[item.经办步骤Step]                
                        const availableOptions = selectValue && selectValue.length > 0 ? item.NodeFlow_AuthorizedUser.filter(
                            (option: any) => !selectValue.some((selected: any) => selected.value === option.value)
                        ) : item.NodeFlow_AuthorizedUser;

                        console.log("item.NodeFlow_AuthorizedUser", item.NodeFlow_AuthorizedUser.length)

                        return (
                            <Fragment key={index}>
                                <Typography sx={{ my: 2 }} >  转交下一步: {item.经办步骤} </Typography>
                                {item.NodeFlow_AuthorizedUser && item.NodeFlow_AuthorizedUser.length == 0 && ( 
                                    <Typography sx={{ my: 2 }} color="text.secondary">此步骤没有设置主办人</Typography>
                                 )}
                                {item.NodeFlow_AuthorizedUser && item.NodeFlow_AuthorizedUser.length > 0 && (   
                                    <Autocomplete
                                        sx={{ my: 2 }}
                                        size="small"
                                        options={availableOptions}
                                        getOptionLabel={(option: any) => option.label}
                                        onChange={(event, newValue: any) => {
                                            const filterValue = {...selectedUsers}
                                            filterValue[item.经办步骤Step] = newValue
                                            setSelectedUsers(filterValue);
                                            
                                            // 清除当前错误状态
                                            if (userErrors && userErrors[item.经办步骤Step]) {
                                                const newErrors = {...userErrors};
                                                delete newErrors[item.经办步骤Step];
                                                setUserErrors(newErrors);
                                            }
                                        }}
                                        renderInput={(params) => (
                                            <TextField
                                                {...params}
                                                label="下一步主办人"
                                                placeholder="搜索或选择..."
                                                variant="outlined"
                                                error={Boolean(userErrors && userErrors[item.经办步骤Step])}  
                                            />
                                        )}
                                        />
                                )} 
                            </Fragment>

                        )
                    })}
                    {nextNodes && nextNodes.length > 0 && (
                        <Fragment>
                            <Button variant="contained" size="small" sx={{ ml: 'auto', mt: 4 }} onClick={()=>{
                                handleToNextStep()
                            }}>开始转交</Button>
                            {newTextErrors && (
                                <Typography sx={{ my: 2, ml: 'auto' }} variant="body2" color="error">{newTextErrors}</Typography>
                            )}
                        </Fragment>
                    )}
                    {(nextNodes == null || nextNodes.length == 0) && (
                        <Fragment>当前步骤没有主办人员,请在流程设计中设置当前步骤的授权访问人员信息.</Fragment>
                    )}
                </Fragment>
            )}
            {endNode == true && (
                <Fragment>
                    当前步骤是最后一步, 点击进行结束当前工作.
                    <Button variant="contained" size="small" sx={{ ml: 'auto', mt: 2 }} onClick={()=>{
                        handleToEndWork()
                    }}>结束工作</Button>
                </Fragment>
                
            )}
        </Box>
      )}
    </Fragment>
  );
};

export default GetNextApprovalUsers;
