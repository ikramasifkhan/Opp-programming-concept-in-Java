package StudentManagementSystem;
/**
 *
 * @author Radi
 */
public class Person {
    public String name;
    public String address;
    public String phoneNumber;
    public String emailAddress;

    public Person(String name, String address, String phoneNumber, String emailAddress) {
        this.name = name;
        this.address = address;
        this.phoneNumber = phoneNumber;
        this.emailAddress = emailAddress;
    }
    @Override
    public String toString(){
        String personInformation=
                "Person name :"+name
                +"\nPerson address:"+address
                +"Contact number"+phoneNumber
                +"Email address"+emailAddress;
        return personInformation;
    }
    
}
