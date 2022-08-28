

-- sql tests
SELECT voucers.id,account_ledgers.name,customers.name,voucers.debit,voucers.credit from voucers
inner join account_ledgers on account_ledgers.id=voucers.ledger_id
left join customers on account_ledgers.relation_with='customers';
SELECT id,name,phone,adress,(ifnull((select sum(voucers.debit-voucers.credit) from voucers
        inner join account_ledgers on voucers.ledger_id=account_ledgers.id and account_ledgers.name='Customer'
        WHERE account_ledgers.name='Customer'
        ),0)) balance from customers







        select voucer.id,voucer.date,voucer.debit,voucer.credit
        from  
        (
        select cast(voucers.id as char) id,voucers.date,voucers.debit,voucers.credit,voucers.ledger_id,voucers.subledger_id
        from voucers where voucers.ledger_id=1 and voucers.subledger_id=9 and voucers.date<=1659117600 
        ) voucer  where voucer.id between 11 and 161 order by voucer.date asc
