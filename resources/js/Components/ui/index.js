// Global UI Components Registration
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from './accordion'
import { Table, TableBody, TableCaption, TableCell, TableEmpty, TableFooter, TableHead, TableHeader, TableRow } from './table'
import { Button } from './button'
import { Badge } from './badge'
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from './card'
import { Input } from './input'
import { Select, SelectContent, SelectGroup, SelectItem, SelectItemText, SelectLabel, SelectScrollDownButton, SelectScrollUpButton, SelectSeparator, SelectTrigger, SelectValue } from './select'

export {
  // Accordion
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
  // Table
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableEmpty,
  TableFooter,
  TableHead,
  TableHeader,
  TableRow,
  // Button
  Button,
  // Badge
  Badge,
  // Card
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
  // Input
  Input,
  // Select
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectItemText,
  SelectLabel,
  SelectScrollDownButton,
  SelectScrollUpButton,
  SelectSeparator,
  SelectTrigger,
  SelectValue
}

// Function to register all UI components globally
export function registerGlobalComponents(app) {
  // Accordion
  app.component('Accordion', Accordion)
  app.component('AccordionContent', AccordionContent)
  app.component('AccordionItem', AccordionItem)
  app.component('AccordionTrigger', AccordionTrigger)
  
  // Table
  app.component('Table', Table)
  app.component('TableBody', TableBody)
  app.component('TableCaption', TableCaption)
  app.component('TableCell', TableCell)
  app.component('TableEmpty', TableEmpty)
  app.component('TableFooter', TableFooter)
  app.component('TableHead', TableHead)
  app.component('TableHeader', TableHeader)
  app.component('TableRow', TableRow)
  
  // Button
  app.component('Button', Button)
  
  // Badge
  app.component('Badge', Badge)
  
  // Card
  app.component('Card', Card)
  app.component('CardContent', CardContent)
  app.component('CardDescription', CardDescription)
  app.component('CardFooter', CardFooter)
  app.component('CardHeader', CardHeader)
  app.component('CardTitle', CardTitle)
  
  // Input
  app.component('Input', Input)
  
  // Select
  app.component('Select', Select)
  app.component('SelectContent', SelectContent)
  app.component('SelectGroup', SelectGroup)
  app.component('SelectItem', SelectItem)
  app.component('SelectItemText', SelectItemText)
  app.component('SelectLabel', SelectLabel)
  app.component('SelectScrollDownButton', SelectScrollDownButton)
  app.component('SelectScrollUpButton', SelectScrollUpButton)
  app.component('SelectSeparator', SelectSeparator)
  app.component('SelectTrigger', SelectTrigger)
  app.component('SelectValue', SelectValue)
}