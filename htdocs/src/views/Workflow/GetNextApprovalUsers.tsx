import { useState, useEffect, Fragment } from 'react';
import axios from 'axios';
import { Box,Typography,Button,Grid } from '@mui/material';
import { authConfig, defaultConfig } from 'src/configs/auth'
import CircularProgress from '@mui/material/CircularProgress'
import Divider from '@mui/material/Divider'
import { Autocomplete, TextField, Chip } from '@mui/material'
import toast from 'react-hot-toast'

const GetNextApprovalUsers = ({ FlowId, handleReturnButton, flowRecord }: any) => {

  const [loading, setLoading] = useState(true);
  const [nextNodes, setNextNodes] = useState<any[]>([])

  useEffect(() => {
    const fetchWorkItems = async () => {
        try {
            const storedToken = window.localStorage.getItem(defaultConfig.storageTokenKeyName)!
            const response = await axios.post(authConfig.backEndApiHost + 'workflow/start.php?action=GetNextApprovalUsers', { FlowId, processid: flowRecord.processid, runid: flowRecord.id }, {
                headers: {
                  Authorization: storedToken,
                  'Content-Type': 'application/json'
                }
              });
            const data = response.data;
            setLoading(false)
            setNextNodes(data.data)
            console.log("handleToNextStep data", data)  
        } 
        catch (err) {
            setLoading(false)
        } 
        finally {
            setLoading(false)
        } 
    };

    FlowId && FlowId != undefined && fetchWorkItems();

  }, [FlowId]);

  const [selectedUsers, setSelectedUsers] = useState<any>(null);
  const [selectedText, setSelectedText] = useState<string>('');
  const [textErrors, setTextErrors] = useState<any>(null);
  const [userErrors, setUserErrors] = useState<any>(null);

  const handleToNextStep = async () => {

    const newTextErrors: any = {};
    const newUserErrors: any = {};
    nextNodes && nextNodes.map((item: any, index: number)=>{
        if (!selectedUsers || !selectedUsers[item.经办步骤Step] || selectedUsers[item.经办步骤Step].length == 0) {
            newUserErrors[item.经办步骤Step] = '下一步主办人不能为空';
        }
    })
    if (!selectedText || selectedText.trim() === '') {
        setTextErrors("主办说明不能为空");
    }
    setUserErrors(newUserErrors);

    const hasErrors = Object.keys(newTextErrors).length > 0 || Object.keys(newUserErrors).length > 0;

    if(!hasErrors)   {
        try {
            const storedToken = window.localStorage.getItem(defaultConfig.storageTokenKeyName)!
            const response = await axios.post(authConfig.backEndApiHost + 'workflow/start.php?action=GoToNextStep', { FlowId, processid: flowRecord.processid, runid: flowRecord.id, selectedText, selectedUsers }, {
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
        } 
        finally {
            setLoading(false)
        } 
    }
  }

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
      {loading == false && (        
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
            {nextNodes && nextNodes.map((item: any, index: number) => {
                const selectValue = selectedUsers && selectedUsers[item.经办步骤Step]                
                const availableOptions = selectValue ? item.NodeFlow_AuthorizedUser.filter(
                    (option: any) => !selectValue.some((selected: any) => selected.value === option.value)
                ) : item.NodeFlow_AuthorizedUser;

                return (
                    <Fragment>
                        <Typography sx={{ my: 2 }} >  转交下一步: {item.经办步骤} </Typography>                        
                        <Autocomplete
                            multiple
                            size="small"
                            options={availableOptions}
                            getOptionLabel={(option) => option.label}
                            value={selectValue ?? []}
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
                            renderTags={(value, getTagProps) =>
                            value.map((option, index) => (
                                <Chip 
                                    label={option.label}
                                    {...getTagProps({ index })}
                                    size="small"
                                    sx={{ mr: 1 }}
                                />
                            ))
                            }
                            sx={{ my: 2 }}
                        />
                    </Fragment>

                )
            })}
            {nextNodes && (
                <Button variant="contained" size="small" sx={{ ml: 'auto', mt: 2 }} onClick={()=>{
                    handleToNextStep()
                }}>开始转交</Button>
            )}
            {(nextNodes == null || nextNodes.length == 0) && (
                <Fragment>当前步骤没有主办人员,请在流程设计中设置当前步骤的授权访问人员信息.</Fragment>
            )}
        </Box>
      )}
    </Fragment>
  );
};

export default GetNextApprovalUsers;
