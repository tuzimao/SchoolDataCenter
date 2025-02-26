// ** MUI Imports
import React, { Fragment } from 'react';
import Typography from '@mui/material/Typography'
import Box from '@mui/material/Box'

// ** Icon Imports
import IconButton from '@mui/material/IconButton'
import Avatar from '@mui/material/Avatar'
import { useTranslation } from 'react-i18next'

import Dialog from '@mui/material/Dialog'
import DialogTitle from '@mui/material/DialogTitle'
import DialogContent from '@mui/material/DialogContent'
import DialogActions from '@mui/material/DialogActions'

import Button from '@mui/material/Button'
import CloseIcon from '@mui/icons-material/Close'
import Grid from '@mui/material/Grid'
import Select from '@mui/material/Select'
import MenuItem from '@mui/material/MenuItem'
import TextField from '@mui/material/TextField'

import { appTemplates, appGroup } from 'src/views/AiFlow/data/appTypeTemplate'

const NewApp = ({ NewOpen, setNewOpen, handleAddApp, AppNewForm, setAppNewForm }: any) => {
  const { t } = useTranslation()
  const appGroupList = appGroup.split(',')
  
  return (
    <Dialog maxWidth='xs' fullWidth open={NewOpen} onClose={
        () => { setNewOpen(false) }
    }>
        <DialogTitle>
            <Box display="flex" alignItems="center">
            <Typography >{t('New App') as string}</Typography>
            <Box position={'absolute'} right={'5px'} top={'1px'}>
                <IconButton size="small" edge="end" onClick={
                    () => { setNewOpen(false) }
                } aria-label="close">
                <CloseIcon />
                </IconButton>
            </Box>
            </Box>
        </DialogTitle>
        <DialogContent sx={{ pb: 2 }}>
            <Fragment>
                <Grid item sx={{pt: 0}} xs={12}>
                    <Box display="flex" mb={1} alignItems="center">
                        <Typography sx={{pb: 1}}>{t('Name') as string}</Typography>
                        <span style={{ paddingTop: '5px', color: 'red', marginLeft: '3px' }}>*</span>
                    </Box>
                    <TextField
                        size='small'
                        InputProps={{ inputProps: { min: 0, max: 100 } }}
                        value={AppNewForm.name}
                        sx={{ width: '100%', resize: 'both', '& .MuiInputBase-input': { fontSize: '0.875rem' } }}
                        onChange={(e: any) => {
                            setAppNewForm((prevState: any)=>({
                                ...prevState,
                                name: e.target.value as string
                            }))
                            console.log("e.target.value", e.target.value);
                        }}
                    />
                </Grid>
                <Grid item sx={{pt: 0}} xs={12}>
                    <Box display="flex" mb={1} alignItems="center">
                        <Typography sx={{pb: 1}}>{t('groupOne') as string}</Typography>
                        <span style={{ paddingTop: '5px', color: 'red', marginLeft: '3px' }}>*</span>
                    </Box>
                    <TextField
                        size='small'
                        InputProps={{ inputProps: { min: 0, max: 100 } }}
                        value={AppNewForm.groupOne || 'General'}
                        sx={{ width: '100%', resize: 'both', '& .MuiInputBase-input': { fontSize: '0.875rem' } }}
                        onChange={(e: any) => {
                            setAppNewForm((prevState: any)=>({
                                ...prevState,
                                groupOne: e.target.value as string
                            }))
                            console.log("e.target.value", e.target.value);
                        }}
                    />
                </Grid>
                <Grid item sx={{pt: 1}} xs={12}>
                    <Box display="flex" mb={1} alignItems="center">
                        <Typography sx={{pb: 1}}>{t('groupTwo') as string}</Typography>
                        <span style={{ paddingTop: '5px', color: 'red', marginLeft: '3px' }}>*</span>
                    </Box>
                    <Select 
                        size="small" 
                        value={AppNewForm.groupTwo}
                        fullWidth
                        onChange={(e: any) => {
                            if(e.target.value != null) {
                                setAppNewForm((prevState: any)=>({
                                    ...prevState,
                                    groupTwo: e.target.value as string
                                }))
                            }
                        }}
                        >
                        <MenuItem value={"Default"}>{t("Default")}</MenuItem>
                        {appGroupList.map((item: any, indexItem: number)=>{
                            return <MenuItem value={item} key={`${indexItem}`}>{item}</MenuItem>
                        })}
                    </Select>
                </Grid>
                <Grid item sx={{pt: 1}} xs={12}>
                    <Box display="flex" mb={1} alignItems="center">
                        <Typography sx={{pb: 1}}>{t('Select from templates') as string}</Typography>
                        <span style={{ paddingTop: '10px', color: 'red', marginLeft: '3px' }}>*</span>
                    </Box>
                    
                    {appTemplates.map((item: any, index: number) => {
                        
                        return (
                            <Fragment key={index}>
                                <Box sx={{ display: 'flex', alignItems: 'center', cursor: 'pointer', my: 2, px: 2, pt: 1, borderRadius: '8px', border: theme => `${item.type === AppNewForm.flowType ? '3px dashed' : '1px solid'}  ${theme.palette.mode === 'light' ? 'rgba(93, 89, 98, 0.22)' : 'rgba(247, 244, 254, 0.14)'}` }}
                                    onClick={()=>{
                                        setAppNewForm((prevState: any)=>({
                                            ...prevState,
                                            flowType: item.type as string
                                        }))
                                    }} >
                                    <Avatar src={item.avatar } sx={{ mr: 3, width: 42, height: 42 }} />
                                    <Box
                                        sx={{
                                            width: '100%',
                                            display: 'flex',
                                            flexWrap: 'wrap',
                                            alignItems: 'center',
                                            justifyContent: 'space-between',
                                            pb: 4
                                        }}
                                    >
                                        <Box sx={{ mr: 2, display: 'flex', mb: 0.4, flexDirection: 'column' }}>
                                            <Typography sx={{ 
                                                fontWeight: 500,
                                                lineHeight: 1.71,
                                                letterSpacing: '0.22px',
                                                fontSize: '0.875rem !important',
                                                maxWidth: '200px',
                                                overflow: 'hidden',
                                                textOverflow: 'ellipsis',
                                                whiteSpace: 'nowrap' 
                                            }} >
                                                {t(item.name)}
                                            </Typography>
                                            <Box
                                                sx={{
                                                    display: 'flex',
                                                    alignItems: 'center',
                                                    '& svg': { mr: 1, color: 'text.secondary', verticalAlign: 'middle' }
                                                }}
                                            >
                                                <Typography 
                                                    variant='caption' 
                                                    sx={{ 
                                                        display: '-webkit-box',
                                                        WebkitBoxOrient: 'vertical',
                                                        WebkitLineClamp: 3,
                                                        overflow: 'hidden',
                                                        textOverflow: 'ellipsis'
                                                    }}
                                                >
                                                    {t(item.intro)}
                                                </Typography>
                                            </Box>
                                        </Box>
                                    </Box>
                                </Box>
                            </Fragment>
                        );
                    })}
                </Grid>
            </Fragment>
        </DialogContent>
        <DialogActions>
            <Button size="small" variant='outlined' onClick={
                () => { setNewOpen(false) }
            }>
            {t("Cancel")}
            </Button>
            <Button size="small" variant='contained' onClick={
                () => { 
                    setNewOpen(false)
                    handleAddApp() 
                }
            }>
            {t("Confirm")}
            </Button>
        </DialogActions>
    </Dialog>
  )
}

export default NewApp
